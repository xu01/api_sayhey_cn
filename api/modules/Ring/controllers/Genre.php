<?php
/************************************
 * Project:      yaf
 * FileName:     Genre.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/19 15:27
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

class GenreController extends BaseRingController {

    public function getGenresAction() {
        fang_p($this->model->fetchAll());
        fang_p($this->model);
    }


}