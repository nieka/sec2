<?php
/**
 * Created by PhpStorm.
 * User: niek
 * Date: 8-4-2015
 * Time: 17:40
 */
class Trainingsschema   {
    private $_db;

    public function __construct(){
        $this->_db = Db::getInstance();
    }

    public function getAllTrainingsschemas(){
        $this->_db->getAll('trainingsschema');
        return $this->_db->results();
    }

    public function createTTrainingsschema($soort,$afstand,$omschrijving,$nivo){
        if($nivo === "geen"){
            if($this->_db->insert('trainingsschema',array(
                'Soort_Soort'   =>  $soort,
                'Afstand'       =>  $afstand,
                'Omschrijving'  =>  $omschrijving,
                'Niveau'        =>  $nivo
            ))){
                return true;
            } else {
                return false;
            }
        } else {
            if($this->_db->insert('trainingsschema',array(
                'Soort_Soort'   =>  $soort,
                'Afstand'       =>  $afstand,
                'Omschrijving'  =>  $omschrijving,
                'Niveau'        =>  $nivo
            ))){
                return true;
            } else {
                return false;
            }
        }

    }
    public function updateTrainingsschema($soort,$afstand,$omschrijving,$ID, $nivo){
        if($nivo === "geen"){
            return $this->_db->update('trainingsschema',$ID,array(
                'Soort_Soort'   =>  $soort,
                'Afstand'       =>  $afstand,
                'Omschrijving'  =>  $omschrijving
            ),'TraingId');
        } else {
            return $this->_db->update('trainingsschema',$ID,array(
                'Soort_Soort'   =>  $soort,
                'Afstand'       =>  $afstand,
                'Omschrijving'  =>  $omschrijving,
                'Niveau'        =>  $nivo
            ),'TraingId');
        }
    }

    public function deleteTrainingsschema($id){
        $workoutclass = new Workout();
        $workouts = $workoutclass->getWorkoutForTrainingSchema($id);

        foreach($workouts as $workout){
            $workoutclass->deleteWorkout($workout->ID);
        }

        if($this->_db->delete('trainingsschema',array('TraingId','=',$id))) {
            return true;
        } else {
            return false;
        }
    }

    public function getTrainingsschemaById($id){
        if($this->_db->get('trainingsschema',array('TraingId','=',$id))){
            return $this->_db->first();
        }
    }

    public function getTrainingsschemaBySoort($id){
        if($this->_db->get('trainingsschema',array('Soort_Soort','=',$id))){
            return $this->_db->first();
        }
    }

    public function getTrainingsschemaByparameters($soort, $afstand,$nivo){
        if($this->_db->query('SELECT * FROM `trainingsschema` WHERE `Soort_Soort` = ? AND `Afstand` = ? AND `Niveau` = ?',array(
            'Soort_Soort'   => $soort,
            'Afstand'       => $afstand,
            'Niveau'        => $nivo

        ))){
            return $this->_db->first();
        }
    }
}

?>



