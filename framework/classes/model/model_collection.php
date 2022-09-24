<?php
    namespace framework\model;

    use ArrayAccess;
    use RuntimeException;

    class model_collection implements ArrayAccess {
        protected $items = [];

        public function offsetSet($offset, $value) {
            if (!$value instanceof Model) {
                throw new RuntimeException('Attempting to add non model to model collection');
            }

            if ($offset === null) {
                $this->items[] = $value;
            } else {
                $this->items[$offset] = $value;
            }
        }

        public function offsetExists($offset) {
            return isset($this->items[$offset]);
        }

        public function offsetUnset($offset) {
            unset($this->items[$offset]);
        }

        public function offsetGet($offset) {
            return $this->items[$offset] ?? null;
        }

        public function to_array() {
            $arr = [];
            foreach ($this->items as $model) {
                $arr[] = $model->to_array();
            }

            return $arr;
        }
    }

