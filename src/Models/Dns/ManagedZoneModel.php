<?php

namespace Glamstack\GoogleCloud\Models\Dns;

use Exception;
use Illuminate\Support\Facades\Validator;

class ManagedZoneModel
{
    public function get(array $options): object
    {
        $validator = Validator::make($options,
            [
                'managed_zone' => 'required|string',
                'project_id' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            throw new Exception($validator->messages()->first());
        }
        $path_parameters = ['managed_zone', 'project_id'];

        return $this->createReturnValue($path_parameters, $options);
    }

    /**
     * @throws Exception
     */
    public function list($options): object
    {
        $validator = Validator::make($options,
            [
                'project_id' => 'required|string',
            ],
        );

        if ($validator->fails()) {
            throw new Exception($validator->messages()->first());
        }

        $path_parameters = ['project_id'];

        return $this->createReturnValue($path_parameters, $options);
    }

    /**
     * @throws Exception
     */
    public function create(array $options): object
    {
        $path_parameters = ['project_id'];

        $validator = Validator::make($options, [
            'project_id' => 'required|string',
            'name' => 'required|string',
            'dns_name' => 'required|string',
            'visibility' => 'required|string|in:public,private',
            'dnssec_config_state' => 'required|string|in:on,off',
            'description' => 'required|string'
        ],[
            'visibility.in' => 'Available visibilities are public and private',
            'dnssec_config_state.in' => 'Available visibilities are public and private'
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->messages()->first());
        }

        return $this->createReturnValue($path_parameters, $options);
    }



    public function delete(array $options): object
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

    public function update(array $options): object
    {
        $validator = Validator::make($options,
            [
                'managed_zone' => 'required|string',
                'project_id' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            throw new Exception($validator->messages()->first());
        }

        $path_parameters = ['managed_zone', 'project_id'];

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
