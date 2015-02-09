<?php
/************************************
 * Project:      yaf
 * FileName:     Ring.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/19 17:35
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

use Strever\API\Response;
class RingController extends BaseRingController {

    /**
     * 单条铃声详情
     */
    public function getByIdAction() {
        $ringId = $this->getRequest()->getParam('ringId',1);
        $ring = $this->model->getById($ringId);
        Response::show($ring);
    }

    /**
     * genre/getByTime/order/[asc|dec]
     * 最新铃声
     */
    public function getByTimeAction() {
        $order = $this->getRequest()->getParam('order','desc');
        $rowCount = $this->getRequest()->getParam('rowCount',20);
        if($order == "asc") $order = true;
        if($order == "desc") $order = false;
        $rings = $this->model->getByTime($order,$rowCount);
        Response::show($rings);
    }

    /**
     * 最热铃声
     */
    public function getByDLNumAction() {
        $rowCount = $this->getRequest()->getParam('rowCount',20);
        $rings = $this->model->getByDLNum($rowCount);
        Response::show($rings);
    }

    /**
     * 好评铃声
     */
    public function getBySupportAction() {
        $rowCount = $this->getRequest()->getParam('rowCount',20);
        $rings = $this->model->getBySupport($rowCount);
        Response::show($rings);
    }

    public function getByGenreIdAction() {
        $genreId = $this->getRequest()->getParam('genreId');
        $currentPage = $this->getRequest()->getParam('pn');
        $genreId = intval($genreId)?$genreId:1;
        $currentPage = intval($currentPage)?$currentPage:1;
        $rings = $this->model->getByGenreId($genreId,$currentPage);
        Response::show($rings);
    }

    public function getAllGenreAction() {
        $genreModel = new \Ring\GenreModel();
        $genres = $genreModel->getGenres();
        $data = array();
        foreach($genres as $genre) {
            $rings = $this->model->getRandomByGenreId($genre['genre_id'],20);
            $genre['rings'] = $rings;
            $data[] = $genre;
        }
        Response::show($data);
    }

    public function getByArtistIdAction() {
        $artistId = $this->getRequest()->getParam('artistId',1792);
        $currentPage = $this->getRequest()->getParam('pn',1);
        $rings = $this->model->getByArtistId(intval($artistId),intval($currentPage));
        Response::show($rings);
    }

    public function supportAction() {
        $ring_id = $this->getRequest()->getParam('ringId');
        if($ring_id) {
            if($id = $this->model->support(intval($ring_id))) {
                Response::show($id);
            }else Response::error(468,"评分失败");
        }
        Response::error(468,"评分失败");
    }

    public function downloadAction() {
        $hash = $this->getRequest()->getParam('hash');
        $ext = $this->getRequest()->getParam('ext','mp3');
        //http://ring.appvv.com/$ring[hash].mp3
        $filename = $hash.'.'.$ext;
        $dllink = "http://ring.appvv.com/{$filename}";
        //下载逻辑
        if($this->model->updateDlNum($hash)) {
            header("Content-Type: application/force-download");
            header("Content-Disposition: attachment; filename=".basename($dllink));
            readfile($dllink);
            exit;
        }
    }

}