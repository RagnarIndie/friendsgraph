<?php
/**
 * Created by PhpStorm.
 * User: Ruslan Sazonov
 * Date: 20/12/2015
 * Time: 17:02
 */

namespace FriendsGraph\Model;


class FriendshipRequest extends BaseModel
{
    protected $fields = [
        'required' => [
            'id', 'from_user_id', 'to_user_id', 'created'
        ]
    ];

    public function getById($user_id, $request_id)
    {
        $request = false;

        if (!empty($user_id) && !empty($request_id)) {
            $query = "MATCH (user:User)-[request:REQUESTS_FRIENDSHIP]-()
                        WHERE user.id = {user_id} AND request.id = {request_id}
                        RETURN user,request";
            $params = [
                'user_id' => $user_id,
                'request_id' => $request_id
            ];
            $client = $this->db->sendCypherQuery($query, $params);
            $result = $client->getResult();

            if ($result->getNodesCount()) {
                $request = $result->getSingleNode('User')
                    ->getSingleRelationship('REQUESTS_FRIENDSHIP')
                    ->getProperties();
            }
        }

        return $request;
    }

    public function getAllByUserId($user_id)
    {
        $allRequests = false;

        if ($user_id) {
            $params = ['user_id' => $user_id];

            //Get ingoing friendship requests
            $inQuery = 'MATCH (user:User)<-[in:REQUESTS_FRIENDSHIP]-()
                        WHERE user.id = {user_id}
                        RETURN in';
            $client = $this->db->sendCypherQuery($inQuery, $params);
            $result = $client->getResult();

            if ($result->getNodesCount()) {
                $rows = $client->getRows();
                $allRequests['in'] = $rows['in'];
            }

            //Get outgoing friendship requests
            $outQuery = 'MATCH (user:User)-[out:REQUESTS_FRIENDSHIP]->()
                        WHERE user.id = {user_id}
                        RETURN out';
            $client = $this->db->sendCypherQuery($outQuery, $params);
            $result = $client->getResult();

            if ($result->getNodesCount()) {
                $rows = $client->getRows();
                $allRequests['out'] = $rows['out'];
            }
        }

        return $allRequests;
    }

    public function create(array $request)
    {
        if ($this->validate($request)) {
            $query = 'MATCH (from:User), (to:User)
                        WHERE from.id = {from_user_id} AND to.id = {to_user_id}
                        CREATE (from)-[:REQUESTS_FRIENDSHIP {id: {id}, from: {from_user_id}, to: {to_user_id}, created: {created}}]->(to)';

            $this->db->sendCypherQuery($query, $request);

            return true;
        } else {
            return false;
        }
    }

    public function delete($request_id)
    {
        if (!empty($request_id)) {
            $query = 'MATCH ()-[r:REQUESTS_FRIENDSHIP]-() WHERE r.id = {request_id} DETACH DELETE r';
            $this->db->sendCypherQuery($query, ['request_id' => $request_id]);

            return true;
        } else {
            return false;
        }
    }
}