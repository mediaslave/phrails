<?php

class OneTwentyWordRule extends WordCountRangeRule {

    public function __construct($message='') {
        parent::__construct(0, 120, $message);
    }
}
