<?php

namespace Glamstack\GoogleCloud\Models\Dns;

use Exception;
use Illuminate\Support\Facades\Validator;

class RecordSetModel
{
    public function get(array $options): object
    {
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
        $path_parameters = ['managed_zone', 'project_id', 'name', 'type'];

        return $this->createReturnValue($path_parameters, $options);
    }

    /**
     * @throws Exception
     */
    public function create(array $options): object
    {
        $default_ttl = 300;

        $path_parameters = ['managed_zone', 'project_id'];

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

        return $this->createReturnValue($path_parameters, $options);
    }

    /**
     * @throws Exception
     */
    public function list($options): object
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

        $path_parameters = ['managed_zone', 'project_id'];

        return $this->createReturnValue($path_parameters, $options);
    }

    public function delete(array $options): object
    {
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

        $path_parameters = ['managed_zone', 'project_id', 'name', 'type'];

        return $this->createReturnValue($path_parameters, $options);
    }

    protected function createReturnValue(array $path_parameters, $request_data): object
    {
        $final_path_parameters = (object) [];
        foreach($path_parameters as $parameter){
            $final_path_parameters->$parameter = $request_data[$parameter];
        }

        $final_request_data = $request_data;
        foreach($path_parameters as $parameter){
            unset($final_request_data[$parameter]);
        }

        return (object)[
            'path_parameters' =>  $final_path_parameters,
            'request_data' => $final_request_data
        ];
    }
}
