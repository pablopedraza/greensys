<?php
class UploadCsv extends CFormModel
{
	public $file;

	public function rules()
	{
        return array(
            array('file', 'file', 'types'=>'csv'),
        );
    }
}
