<?php

namespace ActivitiesTasks\Controllers;

use \Espo\Core\Exceptions\Error;
use \Espo\Core\Exceptions\BadRequest;

class Lead extends \Espo\Core\Controllers\Record
{
    public function postActionConvert($params, $data, $request)
    {
        if (empty($data->id)) {
            throw new BadRequest();
        }
        if (empty($data->records)) {
            $data->records = (object)[];
        }
        $entity = $this->getRecordService()->convert($data->id, $data->records);

        if (!empty($entity)) {
            return $entity->toArray();
        }
        throw new Error();
    }

    public function postActionGetConvertAttributes($params, $data, $request)
    {
        if (empty($data->id)) {
            throw new BadRequest();
        }

        return $this->getRecordService()->getConvertAttributes($data->id);
    }
}
