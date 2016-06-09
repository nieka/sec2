<?php
/**
 * Created by PhpStorm.
 * User: niek
 * Date: 6-6-2015
 * Time: 10:04
 */
class Workout   {
    private $_db;

    public function __construct(){
        $this->_db = Db::getInstance();
    }

    public function getWorkoutForTrainingSchema($id){
        $this->_db->get('workout', array('trainingsschema_TraingId','=',$id));
        return $this->_db->results();
    }

    public function addWorkout($id,$week,$nummer,$tijd,$soort){
        $this->_db->insert('workout',array(
            'Week'                      =>  $week,
            'nummer'                    =>  $nummer,
            'trainingsschema_TraingId'  =>  $id
        ));

        $this->_db->query('select * from workout where Week = ? AND nummer = ? AND  trainingsschema_TraingId = ? ',array(
            'Week'                      =>  $week,
            'nummer'                    =>  $nummer,
            'trainingsschema_TraingId'  =>  $id
        ));

        $result = $this->_db->first();
        $workoutid = $result->ID;
        //voegt de onderdelen toe
        for($i=0; $i< count($tijd); $i++){
            $this->_db->insert('onderdeel', array(
                'LoopSoorten_Soort' =>  $soort[$i],
                'Tijd'              =>  $tijd[$i],
                'Workout_ID'        =>  $workoutid
            ));
        }
    }

    public function deleteWorkout($id){
        //Verwijderd onderdelen
        $this->_db->delete('onderdeel',array('Workout_ID','=', $id));
        //verwijderd workout
        $this->_db->delete('workout',array('ID','=',$id));
    }

    public function getWorkout($id){
        $this->_db->get('workout',array('ID','=',$id));

        return $this->_db->first();
    }

    public function getonderdelbyWorkout($id){
        $this->_db->get('onderdeel',array('Workout_ID','=', $id));

        return $this->_db->results();
    }

}

?>