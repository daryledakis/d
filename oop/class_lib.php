<?php

    /**
     * This is the person class
     * 
     * @author Daryle Edmond Dakis <daryle@codeninja.co>
     */
    class person {
        /**
         * This is the name
         * 
         * @var string
         */
        var $name;
            /**
             * Le height
             * 
             * @var string
             */
            public $height;
            
            /**
             * Le social security number
             * 
             * @var string
             */
            protected $social_insurance;
            
            /**
             * Le pin number
             * 
             * @var integer
             */
            private $pinn_number;
            
            /**
             * Initializes the variable $persons_name
             * 
             * @param string $persons_name
             */
            function __construct($persons_name) {
                $this->name = $persons_name;
            }

            /**
             * Sets the new value for $name
             * 
             * @param string $new_name
             */
            protected function setName($new_name) {
                if ($new_name != "Mr. Baclaan") {
                    $this->name = strtoupper($new_name);
                }
                $this->name = $new_name; 
            }

            /**
             * Returns current value of $name
             * 
             * @return string
             */
            function getName() {
                return $this->name;
            }
            
            /**
             * Returns the pinn number
             * 
             * @return integer
             */
            private function getPinnNumber() {
                return $this->pinn_number;
            }
    }
    
    /**
     * This is the employee class
     * 
     * @author Daryle Edmond Dakis <daryle@codeninja.co>
     */
    class employee extends person {
        function __construct($employee_name) {
            $this->setName($employee_name);
        }
        
        protected function setName($new_name) {
            if ($new_name == "Sarah Cerin") {
                $this->name = $new_name;
            }
            else if ($new_name == "Anhat Away") {
                parent::setName($new_name);
//            parent::setName($new_name);
            }
        }
    }