<?php

class AntoursError extends \Exception {
    private $errorCodes = array(
        'NON_WIDTH' => 100,
        'MAX_SIZE_EXCEED' => 110,
        'OUT_OF_HEIGHT_RANGE' => 120,
        'NON_EXTENSION' => 130,
        'NON_POST_ID' => 140,
        'NON_FILE_UPLOADED' => 150,
        'NON_ATTACHED_FILE' => 160,
        'UNKNOWN' => 400
    );

    private $errorMessages = null;

    public function __construct($codeName, $params = array(), $message = null, Exception $previous = null) {
        $code = $this->getError($codeName);
        $this->initializeErrorMessages();

        if (!isset($message)) {
            $template = $this->errorMessages[$codeName];
            $message = str_replace(array("{param1}", "{param2}"), $params, $template);
        }
        
        parent::__construct($message, $code, $previous);
    }

    private function initializeErrorMessages() {
        $this->errorMessages = array(
            'NON_WIDTH' => __("Your image is smaller than {param1}, at least should be {size}px", "antours_error_non_width"),
            'MAX_SIZE_EXCEED' => __("Your image must be {param1}", "antours_error_max_size_exceed"),
            'OUT_OF_HEIGHT_RANGE' => __("Your image height size must be between {param1}-{param2}", "antours_error_out_range_height"),
            'NON_EXTENSION' => __("Your type image is not allowed, you should use {param1}", "antours_error_extension"),
            'NON_POST_ID' => __("You must provide an post id to attach the image", "antours_error_post_id"),
            'NON_FILE_UPLOADED' => __("Your image wasn't uploaded: {param1}", "antours_error_non_file_uploaded"),
            'NON_ATTACHED_FILE' => __("Your image couldn't be attached to the post", "antours_error_non_attached_file"),
            'UNKNOWN' => __("An unknown error occurred", "antours_error_unknown")
        );
    }

    public function getError($code) {
        $errorCode = $this->errorCodes[$code];
        if (!isset($errorCode)) {
            $errorCode = $this->errorCodes['UNKNOWN'];
        }

        return $errorCode;
    }

    public function parseErrorForJSON() {
        return array(
            'error' => array(
                'code' => $this->getCode(),
                'reason' => $this->getMessage(),
            )
        );
    }
}