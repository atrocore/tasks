<?php

namespace ActivitiesTasks\Controllers;

use \Espo\Core\Exceptions\Error,
    \Espo\Core\Exceptions\Forbidden,
    \Espo\Core\Exceptions\BadRequest;

class Activities extends \Espo\Core\Controllers\Base
{
    protected $maxCalendarRange = 123;

    const MAX_SIZE_LIMIT = 200;

    public function actionListCalendarEvents($params, $data, $request)
    {
        if (!$this->getAcl()->check('Calendar')) {
            throw new Forbidden();
        }

        $from = $request->get('from');
        $to = $request->get('to');

        if (empty($from) || empty($to)) {
            throw new BadRequest();
        }

        if (strtotime($to) - strtotime($from) > $this->maxCalendarRange * 24 * 3600) {
            throw new Forbidden('Too long range.');
        }

        $service = $this->getService('Activities');

        $scopeList = null;
        if ($request->get('scopeList') !== null) {
            $scopeList = explode(',', $request->get('scopeList'));
        }

        $userId = $request->get('userId');
        $userIdList = $request->get('userIdList');
        $teamIdList = $request->get('teamIdList');

        if ($teamIdList) {
            $teamIdList = explode(',', $teamIdList);
            return $userResultList = $service->getEventsForTeams($teamIdList, $from, $to, $scopeList);
        }

        if ($userIdList) {
            $userIdList = explode(',', $userIdList);

            $resultList = [];
            foreach ($userIdList as $userId) {
                try {
                    $userResultList = $service->getEvents($userId, $from, $to, $scopeList);
                } catch (\Exception $e) {
                    continue;
                }
                foreach ($userResultList as $item) {
                    $item['userId'] = $userId;
                    $resultList[] = $item;
                }
            }
            return $resultList;
        } else {
            if (!$userId) {
                $userId = $this->getUser()->id;
            }
        }

        return $service->getEvents($userId, $from, $to, $scopeList);
    }

    public function actionListUpcoming($params, $data, $request)
    {
        $service = $this->getService('Activities');

        $userId = $request->get('userId');
        if (!$userId) {
            $userId = $this->getUser()->id;
        }

        $offset = intval($request->get('offset'));
        $maxSize = intval($request->get('maxSize'));

        $entityTypeList = $request->get('entityTypeList');

        $futureDays = intval($request->get('futureDays'));

        $maxSizeLimit = $this->getConfig()->get('recordListMaxSizeLimit', self::MAX_SIZE_LIMIT);
        if (empty($maxSize)) {
            $maxSize = $maxSizeLimit;
        }
        if (!empty($maxSize) && $maxSize > $maxSizeLimit) {
            throw new Forbidden("Max should should not exceed " . $maxSizeLimit . ". Use offset and limit.");
        }

        return $service->getUpcomingActivities($userId, array(
            'offset' => $offset,
            'maxSize' => $maxSize
        ), $entityTypeList, $futureDays);
    }

    public function actionPopupNotifications()
    {
        $userId = $this->getUser()->id;

        return $this->getService('Activities')->getPopupNotifications($userId);
    }

    public function actionRemovePopupNotification($params, $data, $request)
    {
        if (!$request->isPost()) {
            throw new BadRequest();
        }

        if (empty($data->id)) {
            throw new BadRequest();
        }
        $id = $data->id;

        return $this->getService('Activities')->removeReminder($id);
    }

    public function actionList($params, $data, $request)
    {
        if (!$this->getAcl()->check('Activities')) {
            throw new Forbidden();
        }

        $name = $params['name'];

        if (!in_array($name, ['activities', 'history'])) {
            throw new BadRequest();
        }

        if (empty($params['scope'])) {
            throw new BadRequest();
        }
        if (empty($params['id'])) {
            throw new BadRequest();
        }

        $entityType = $params['scope'];
        $id = $params['id'];

        $offset = intval($request->get('offset'));
        $maxSize = intval($request->get('maxSize'));
        $asc = $request->get('asc') === 'true';
        $sortBy = $request->get('sortBy');
        $where = $request->get('where');

        $maxSizeLimit = $this->getConfig()->get('recordListMaxSizeLimit', self::MAX_SIZE_LIMIT);
        if (empty($maxSize)) {
            $maxSize = $maxSizeLimit;
        }
        if (!empty($maxSize) && $maxSize > $maxSizeLimit) {
            throw new Forbidden("Max should should not exceed " . $maxSizeLimit . ". Use offset and limit.");
        }

        $scope = null;
        if (is_array($where) && !empty($where[0]) && $where[0] !== 'false') {
            $scope = $where[0];
        }

        $service = $this->getService('Activities');

        $methodName = 'get' . ucfirst($name);

        return $service->$methodName($entityType, $id, array(
            'scope' => $scope,
            'offset' => $offset,
            'maxSize' => $maxSize,
            'asc' => $asc,
            'sortBy' => $sortBy,
        ));
    }
}

