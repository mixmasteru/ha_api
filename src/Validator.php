<?php
namespace ha;
use ha\Exception\Parameter as ParameterException;

/**
 * Class Validator
 * @package ha
 */
class Validator
{

    protected $arr_types;

    /**
     *
     */
    public function __construct()
    {
        foreach (filter_list() as $value)
        {
            $this->arr_types[filter_id($value)] = $value;
        }
    }

    /**
     * trys to create DateTime from string and returns it
     *
     * @param string $date_to_check
     * @return \DateTime
     * @throws ParameterException
     */
    public function checkDate($date_to_check){

        $date = \DateTime::createFromFormat("Ymd-His", $date_to_check);

        if($date === false || array_sum($date->getLastErrors()) > 0)
        {
            throw new ParameterException("no valid date: ".$date_to_check,0);
        }

        return $date;
    }

    /**
     * @param $parameter
     * @param $name
     * @param $type_id
     * @return mixed
     * @throws ParameterException
     */
    public function checkParam($parameter,$name,$type_id)
    {
        if(!isset($this->arr_types[$type_id]))
        {
            throw new \RuntimeException($type_id." is not a valid filter type");
        }

        $parameter = filter_var($parameter,$type_id);

        if($parameter === false)
        {
            $msg = "wrong type of parameter: ".$name." must be ".$this->arr_types[$type_id];
            throw new ParameterException($msg);
        }

        return $parameter;
    }
}