<?php

class viewHelper extends View {

    public function __construct() {

    }
    
    public function getDetailByFieldForAlbum($id = '', $firstField = '') {

        $albumJsonFile = PHY_LETTER_URL . $id . '.json';
        $albumJsonData = file_get_contents($albumJsonFile);
        $data = json_decode($albumJsonData,true);            
    
        if (isset($data[$firstField])) {
      
            return $data[$firstField];
        }

        return '';
    }

    public function getDetailByField($json = '', $firstField = '', $secondField = '') {

        $data = json_decode($json, true);

        if (isset($data[$firstField])) {
      
            return $data[$firstField];
        }
        elseif (isset($data[$secondField])) {
      
            return $data[$secondField];
        }

        return '';
    }

    public function getLettersCount($id = '') {

        $count = sizeof(glob(PHY_LETTER_URL . $id . '/*.json'));
        return ($count > 1) ? $count . ' Letters' : $count . ' Letter';
    }

    public function getActualID($combinedID) {

        return preg_replace('/^(.*)__/', '', $combinedID);
    }
    
    public function getAlbumID($combinedID){

        return preg_replace('/__(.*)/', '', $combinedID);   
    }

    public function includeRandomThumbnail($id = '') {
        $letters = glob(PHY_LETTER_JPG_URL . $id . '/*',GLOB_ONLYDIR);
        $randNum = rand(0, sizeof($letters) - 1);
        $letterSelected = $letters[$randNum];
        $pages = glob($letterSelected . '/thumbs/*.JPG');
        $randNum = rand(0, sizeof($pages) - 1);
        $pageSelected = $pages[$randNum];
        return str_replace(PHY_LETTER_JPG_URL, LETTER_JPG_URL, $pageSelected);
    }

    public function includeRandomThumbnailFromLetter($id = '') {

        $ids = preg_split('/__/', $id);
        $pages = glob(PHY_LETTER_JPG_URL . $ids[0] . '/' . $ids[1] .  '/thumbs/*.JPG');
        $randNum = rand(0, sizeof($pages) - 1);
        $pageSelected = $pages[$randNum];

        return str_replace(PHY_LETTER_JPG_URL, LETTER_JPG_URL, $pageSelected);
    }

    public function displayFieldData($json, $auxJson='') {

        $data = json_decode($json, true);
        
        if ($auxJson) $data = array_merge($data, json_decode($auxJson, true));

        $pdfFilePath = '';
        if(isset($data['id'])) {

            $actualID = $this->getActualID($data['id']);
            $pdfFilePath = LETTER_JPG_URL . $data['albumID'] . '/' . $actualID . '/index.pdf'; 
            $data['id'] = $data['albumID'] . '/' . $data['id'];
            unset($data['albumID']);
        }

        $html = '';
        $html .= '<ul class="list-unstyled">';

        foreach ($data as $key => $value) {

            if($value){

                if(preg_match('/keyword/i', $key)) {

                    $html .= '<li class="keywords"><strong>' . $key . ':</strong><span class="image-desc-meta">';
                    
                    $keywords = explode(',', $value);
                    foreach ($keywords as $keyword) {
       
                        $html .= '<a href="' . BASE_URL . 'search/field/?description=' . $keyword . '">' . str_replace(' ', '&nbsp;', $keyword) . '</a> ';
                    }
                    
                    $html .= '</span></li>' . "\n";
                }
                else{

                    $html .= '<li><strong>' . $key . ':</strong><span class="image-desc-meta">' . $value . '</span></li>' . "\n";
                }
            }    
        }

        // $html .= '<li>Do you know details about this picture? Mail us at heritage@iitm.ac.in quoting the image ID. Thank you.</li>';

        if($pdfFilePath != ''){
            $html .= '<li><a href="'.$pdfFilePath.'">Click here to view PDF</a></li>'; 
        }

        $html .= '</ul>';

        return $html;
    }

    public function displayThumbs($id){

        $albumID = $this->getAlbumID($id);
        $letterID = $this->getActualID($id);
        $filesPath = PHY_LETTER_JPG_URL . $albumID . '/' . $letterID . '/thumbs/*' . PHOTO_FILE_EXT;

        $files = glob($filesPath);


        echo '<div id="viewletterimages" class="letter_thumbnails">';
        foreach ($files as $file) {

            $mainFile = $file;
            $mainFile = preg_replace('/thumbs\//', '', $mainFile);
            echo '<span class="img-small">';

            echo '<img class="img-responsive" data-original="'.str_replace(PHY_LETTER_JPG_URL, LETTER_JPG_URL, $mainFile).'" src="' . str_replace(PHY_LETTER_JPG_URL, LETTER_JPG_URL, $file) . '" >';

            echo '</span>';
        }
        // echo $albumID . '->' . $letterID;
        echo '</div>';

    }


    public function insertReCaptcha() {

        require_once('vendor/recaptchalib.php');

        $publickey = "6Le_DBsTAAAAACt5YrgWhjW00CcAF0XYlA30oLPc";
        $privatekey = "6Le_DBsTAAAAAH8rvyqjPXU9jxY5YJxXct76slWv";

        echo recaptcha_get_html($publickey);
    }
    
    public function displayDataInForm($json, $auxJson='') {

        $data = json_decode($json, true);
        
        if ($auxJson) $data = array_merge($data, json_decode($auxJson, true));
        
        $count = 0;
        $formgroup = 0;

        foreach ($data as $key => $value) {
            // echo "Key: $key; Value: $value\n";
            $disable = (($key == 'id') || ($key == 'albumID'))? 'readonly' : '';
            echo '<div class="form-group" id="frmgroup' . $formgroup . '">' . "\n";
            echo '<input type="text" class="form-control" name="id'. $count . '[]"  value="' . $key . '"' . $disable  . ' />&nbsp;' . "\n";
            echo '<input type="text" class="form-control" name="id'. $count . '[]"  value="' . $value . '"' . $disable . ' />' . "\n";
            if($disable != "readonly"){
                echo '<input type="button"  onclick="removeUpdateDataElement(\'frmgroup'. $formgroup .'\')" value="Remove" />' . "\n";                
            }
            echo '</div>' . "\n";
            $count++;
            $formgroup++;
        }

        echo '<div id="keyvalues">' . "\n";
        echo '</div>' . "\n";
        echo '<input type="button" id="keyvaluebtn" onclick="addnewfields(keyvaluebtn)" value="Add New Fields" />' . "\n";
        echo '<input type="submit" id="submit" value="Update Data" />' . "\n";
    }

}




?>
