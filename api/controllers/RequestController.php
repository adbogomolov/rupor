<?php

require_once 'BaseApiController.php';

class RequestController extends BaseApiController {

    public function actionAddRequest() {
        $this->_checkRequest($this->request, array('title', 'description'));

        if (isset($this->request->token)) {

            $user = $this->_authorize($this->request->token);
            $newRequest = RequestAdmin::addRequest($this->request, $user->id);
        } else {

            $newRequest = RequestAdmin::addRequest($this->request);
        }
        if ($newRequest && !$newRequest->request->lat) {

            Api::sendResponse(Api::INVALID);
        }
        if ($newRequest->error) {

            Api::sendResponse(Api::INVALID);
        }
        if (isset($this->request->tags)) {

            RequestAdmin::tagRequest($this->request->tags, $newRequest->request->id);
        }
        if (isset($this->request->images)) {

            foreach ($this->request->images as $key => $image) {
                $filename = $newRequest->id . _($key + 1) . '.jpg';
                $path = Yii::app()->getBasePath() . Yii::app()->params->itemAt('imagesPath') . $filename;

                $file = fopen($path, 'rw');
                fwrite($file, base64_decode($image));
                fclose($file);

                RequestAdmin::saveFile($filename, $newRequest->id);
            }
        }
        if (isset($user)) {
            $request = RequestAdmin::additionalInfo(array($newRequest->request), $user->id);
        } else {
            $request = RequestAdmin::additionalInfo(array($newRequest->request));
        }
        Api::sendResponse(Api::OK, array('request' => $request[0]));
    }

    public function actionRequestByCoordinates() {

        $this->_checkRequest($this->request, array('lat_top', 'lng_left', 'lat_bottom', 'lng_right'));
        $data = $this->request;
        if ((float) $data->lng_left < (float) $data->lng_right || (float) $data->lat_bottom > (float) $data->lat_top) {
            Api::sendResponse(Api::INVALID);
        }
        $status = isset($data->status) ? $data->status : null;
        $keyword = isset($data->keyword) ? $data->keyword : null;
        $tags = isset($data->tags) ? $data->tags : null;
        $requests = RequestAdmin::getByCoords($data, (array) $status, $keyword, (array) $tags);

        $requests = RequestAdmin::additionalInfo($requests);
        Api::sendResponse(Api::OK, array('requests' => $requests));
    }

    public function actionGetByTag() {

        $firstId = isset($this->request->first_id) ? $this->request->first_id : null;
        $status = isset($this->request->status) ? $this->request->status : null;
        $keyword = isset($this->request->keyword) ? $this->request->keyword : null;
        if (isset($this->request->tags)) {
            $requests = RequestAdmin::getByTag((array) $this->request->tags, $firstId, (array) $status, $keyword);
        } else {
            $requests = RequestAdmin::getAll($firstId, (array) $status, $keyword);
        }
        Api::sendResponse(Api::OK, array('requests' => RequestAdmin::additionalInfo($requests)));
    }

    public function actionTagList() {

        $tags = RequestAdmin::tagList();
        Api::sendResponse(Api::OK, array('tags' => $tags));
    }

    public function actionGetByStatus() {

        $this->_checkRequest($this->request, array('status'));
        $requests = RequestAdmin::getByStatus($this->request->status);
        Api::sendResponse(Api::OK, array('requests' => RequestAdmin::additionalInfo($requests)));
    }

    public function actionAddComment() {

        $this->_checkRequest($this->request, array('token', 'request_id', 'message'));
        $user = $this->_authorize($this->request->token);
        $result = RequestAdmin::addComment($user, $this->request->request_id, $this->request->message);
        if ($result->error) {
            Api::sendResponse(Api::INVALID, array('error' => $result->comment->getErrors()));
        }
        Api::sendResponse(Api::OK, array('comment' => $result->comment));
    }

    public function actionComments() {

        $this->_checkRequest($this->request, array('request_id'));
        Api::sendResponse(Api::OK, array('comments' => CommentAdmin::comments($this->request->request_id)));
    }

    public function actionLatest() {

        if (isset($this->request->limit)) {
            $requests = RequestAdmin::latest($this->request->limit);
        } else {
            $requests = RequestAdmin::latest();
        }

        Api::sendResponse(Api::OK, array('requests' => RequestAdmin::additionalInfo($requests)));
    }

    public function actionGetById() {

        $this->_checkRequest($this->request, array('request_id', 'token'));
        $user = $this->_authorize($this->request->token);
        $request = Request::model()->findAllByPk($this->request->request_id);
        if (!$request) {
            Api::sendResponse(Api::BAD_REQUEST);
        }
        Api::sendResponse(Api::OK, array('request' => RequestAdmin::additionalInfo($request, $user->id)));
    }

    public function actionLike() {

        $this->_checkRequest($this->request, array('token', 'request_id'));
        $user = $this->_authorize($this->request->token);
        LikeAdmin::like($this->request->request_id, $user->id);
        Api::sendResponse(Api::OK, array('count' => LikeAdmin::count($this->request->request_id)));
    }

    public function actionNotification() {

        $this->_checkRequest($this->request, array('token', 'request_id'));
        $user = $this->_authorize($this->request->token);
        if (NotificationAdmin::check($user->id, $this->request->request_id)) {
            Api::sendResponse(Api::OK);
        }
        Api::sendResponse(Api::INTERNAL_ERROR);
    }

    public function actionGetByPeriod() {

        $this->_checkRequest($this->request, array('token', 'from', 'to'));
        $user = $this->_authorize($this->request->token);
        $requests = RequestAdmin::getByPeriod($this->request->from, $this->request->to);
        Api::sendResponse(Api::OK, array('requests' => RequestAdmin::additionalInfo($requests, $user->id)));
    }

    public function actionGetAll() {
        $requests = Request::model()->findAll();
        Api::sendResponse(Api::OK, array('requests' => RequestAdmin::additionalInfo($requests)));
    }

    public function actionInArea() {
        $this->_checkRequest($this->request, array('token', 'lat', 'lng', 'radius'));
        $user = $this->_authorize($this->request->token);
        if (isset($this->request->count)) {
            $requests = RequestAdmin::getInArea($this->request->lat, $this->request->lng, $this->request->radius, $this->request->count);
        } else {
            $requests = RequestAdmin::getInArea($this->request->lat, $this->request->lng, $this->request->radius);
        }
        Api::sendResponse(Api::OK, array('requests' => RequestAdmin::additionalInfo($requests, $user->id)));
    }

    public function actionMyNotified() {
        $this->_checkRequest($this->request, array('token'));
        $user = $this->_authorize($this->request->token);
        $requests = NotificationAdmin::notifiedList($user);
        Api::sendResponse(Api::OK, array('requests' => RequestAdmin::additionalInfo($requests, $user->id)));
    }

}
