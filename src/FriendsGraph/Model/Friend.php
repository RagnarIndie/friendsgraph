<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Sazonov
 * Date: 20/12/2015
 * Time: 23:17
 */

namespace FriendsGraph\Model;

class Friend extends BaseModel
{
    protected $fields = [
        'required' => [],
    ];

    public function getAllByUserId($user_id, $level = 0)
    {
        $friends = false;

        if (!empty($user_id)) {
            $level_q = '';
            $where_not = '';

            //Friends-of-friends but not nearest user friends
            //?level=0|1 is for nearest friends
            //?level=2..n - friends of friends with a given level
            if ($level > 1) {
                $level_q = "*2..{$level}";
                $where_not = 'NOT (user)-[:FRIEND_OF]->(friends) AND';
            }

            $query = 'MATCH (user:User {id: {user_id}})-[:FRIEND_OF'.$level_q.']->(friends)
                        WHERE '.$where_not.' NOT friends.id = {user_id}
                        RETURN DISTINCT friends';
            $client = $this->db->sendCypherQuery($query, ['user_id' => $user_id]);
            $result = $client->getResult();

            if ($result->getNodesCount()) {
                $rows = $client->getRows();
                $friends = $rows['friends'];
            }
        }

        return $friends;
    }

    public function create($user_id, $friend_id)
    {
        if (!empty($user_id) && !empty($friend_id)) {
            $query = 'MATCH (user:User), (friend:User)
                        WHERE user.id = {user_id} AND friend.id = {friend_id}
                        CREATE (user)-[:FRIEND_OF]->(friend), (user)<-[:FRIEND_OF]-(friend)';
            $params = [
                'user_id' => $user_id,
                'friend_id' => $friend_id
            ];
            $this->db->sendCypherQuery($query, $params);

            return true;
        } else {
            return false;
        }
    }

    public function delete($user_id, $friend_id)
    {
        if (!empty($user_id) && !empty($friend_id)) {
            $query = 'MATCH (user:User)-[r:FRIEND_OF]-(friend:User)
                        WHERE user.id = {user_id} AND friend.id = {friend_id}
                        DETACH DELETE r';
            $params = [
                'user_id' => $user_id,
                'friend_id' => $friend_id
            ];
            $this->db->sendCypherQuery($query, $params);

            return true;
        } else {
            return false;
        }
    }
}