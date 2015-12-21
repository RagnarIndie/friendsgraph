<?php
namespace FriendsGraph\Model;


class User extends BaseModel
{
    protected $fields = [
        'required' => [
            'id', 'name', 'age', 'country', 'countryCode'
        ]
    ];

    public function getAll()
    {
        $query = "MATCH (user:User) RETURN user";
        $client = $this->db->sendCypherQuery($query);
        $result = $client->getResult();

        if ($result-> getNodesCount()) {
            $rows = $client->getRows();

            return $rows['user'];
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        $user = false;

        if (!empty($id)) {
            $query = 'MATCH (user:User) WHERE user.id = {id} RETURN user';
            $params = ['id' => $id];
            $result = $this->db->sendCypherQuery($query, $params)->getResult();

            if ($result->getNodesCount()) {
                $user = $result->getSingleNode('User')->getProperties();
            }
        }

        return $user;
    }

    public function create(array $user)
    {
        if ($this->validate($user)) {
            $user = $this->prepareArrayForCypher($user);
            $query = 'CREATE (user:User {'.$user.'})';
            $this->db->sendCypherQuery($query);

            return true;
        } else {
            return false;
        }
    }

    public function update(array $user)
    {
        if ($this->validate($user)) {
            $query = 'MATCH (user:User {id: {id}})
                        SET user.name = {name},
                            user.age = {age},
                            user.country = {country},
                            user.countryCode = {countryCode}';
            $params = [
                'id' => $user['id'],
                'name' => $user['name'],
                'age' => $user['age'],
                'country' => $user['country'],
                'countryCode' => $user['countryCode']
            ];
            //TODO: check status
            $result = $this->db->sendCypherQuery($query, $params)->getResult();

            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        if (!empty($id)) {
            $query = 'MATCH (user:User {id: {id}}) DELETE user';
            $params = ['id' => $id];
            $this->db->sendCypherQuery($query, $params)->getResult();

            return true;
        } else {
            return false;
        }
    }

    public function generate(array $names, array $countries)
    {
        $results = [];

        if (sizeof($names) > 0 && sizeof($countries) > 0) {
            $isoCodes = array_keys($countries);
            $countries = array_values($countries);

            foreach ($names as $name) {
                $countryRnd = mt_rand(0, count($countries) - 1);
                $user = [
                    'id' => $this->generateId(),
                    'name' => $name,
                    'age' => mt_rand(10, 100),
                    'country' => $countries[$countryRnd],
                    'countryCode' => $isoCodes[$countryRnd]
                ];

                $user = $this->prepareArrayForCypher($user);
                $query = 'CREATE (user:User {'.$user.'})';
                $this->db->sendCypherQuery($query)->getResult();
            }

            $index_q = "CREATE INDEX ON :User(id)";
            $this->db->sendCypherQuery($index_q);
        }

        return $results;
    }

}