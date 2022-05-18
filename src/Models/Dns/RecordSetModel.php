<?php

namespace Glamstack\GoogleCloud\Models\Dns;

use Exception;
use Illuminate\Support\Facades\Validator;

class RecordSetModel
{
    public function get(array $options){
        $validator = Validator::make($options,
            [
                'managed_zone' => 'required|string',
                'project_id' => 'required|string',
                'name' => 'required|string',
                'type' => 'required|string|in:CNAME,A,AAAA',
            ],
            [
                'type.in' => 'Available types are CNAME,A,AAAA'
            ]
        );

        if ($validator->fails()) {
            throw new Exception($validator->messages()->first());
        }

        $path_parameters = (object) [
            'managed_zone' => $options['managed_zone'],
            'project_id' => $options['project_id'],
            'name' => $options['name'],
            'type' => $options['type']
        ];

        $request_data = $options;
        unset($request_data['managed_zone'], $request_data['project_id'], $request_data['name'], $request_data['type']);


        $return_value = (object) [
            'path_parameter' => $path_parameters,
            'request_data' => $request_data
        ];

        return $return_value;
    }

    /**
     * @throws Exception
     */
    public function create(array $options)
    {
        $default_ttl = 300;

        $options['ttl'] = $options['ttl'] ?? $default_ttl;

        $validator = Validator::make($options, [
            'managed_zone' => 'required|string',
            'project_id' => 'required|string',
            'name' => 'required|string',
            'ttl' => 'required|integer',
            'type' => 'required|string|in:CNAME,A,AAAA',
            'rrdatas' => 'required|array'
        ],[
            'type.in' => 'Available types are CNAME,A,AAAA'
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->messages()->first());
        }

        $path_parameters = (object) [
            'managed_zone' => $options['managed_zone'],
            'project_id' => $options['project_id']
        ];

        $request_data = $options;
        unset($request_data['managed_zone']);
        unset($request_data['project_id']);

        $return_value = (object) [
            'path_parameters' => $path_parameters,
            'request_data' => $request_data
        ];

        return $return_value;
    }

    /**
     * @throws Exception
     */
    public function list($options)
    {
        $validator = Validator::make($options,
            [
                'managed_zone' => 'required|string',
                'project_id' => 'required|string',
            ],
        );

        if ($validator->fails()) {
            throw new Exception($validator->messages()->first());
        }

        $path_parameters = (object) [
            'managed_zone' => $options['managed_zone'],
            'project_id' => $options['project_id']
        ];

        $request_data = $options;
        unset($request_data['managed_zone'], $request_data['project_id']);

        return (object) [
            'path_parameters' => $path_parameters,
            'request_data' => $request_data
        ];
    }

    public function delete(array $options){
        $validator = Validator::make($options,
            [
                'managed_zone' => 'required|string',
                'project_id' => 'required|string',
                'name' => 'required|string',
                'type' => 'required|string|in:CNAME,A,AAAA',
            ],
            [
                'type.in' => 'Available types are CNAME,A,AAAA'
            ]
        );

        if ($validator->fails()) {
            throw new Exception($validator->messages()->first());
        }

        $path_parameters = (object) [
            'managed_zone' => $options['managed_zone'],
            'project_id' => $options['project_id'],
            'name' => $options['name'],
            'type' => $options['type']
        ];

        $request_data = $options;
        unset($request_data['managed_zone'], $request_data['project_id'], $request_data['name'], $request_data['type']);


        $return_value = (object) [
            'path_parameter' => $path_parameters,
            'request_data' => $request_data
        ];

        return $return_value;
    }
}
