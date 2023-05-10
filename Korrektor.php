<?php

namespace uzdevid\korrektor;

class Korrektor extends BaseKorrektor {
    
    public function correct() {
        return new Correct(['token' => $this->token]);
    }
}
