<?php
/**
 * Created by PhpStorm.
 * User: niek
 * Date: 8-4-2015
 * Time: 12:08
 */
class Soort   {
    private $_db;

    public function __construct(){
        $this->_db = Db::getInstance();
    }

    public function getAllsoorts(){
        $this->_db->getAll('soort');
        return $this->_db->results();
    }

    public function makesoort($soort){
        if($this->_db->insert('soort',array(
            'Soort'   => $soort
        ))){
            return true;
        } else {
            return false;
        }
    }

    public function createTSoort($soort,$event,$datum,$schema){
        if($event === "Yes"){
            if($datum != ""){
                if($schema === "geen"){
                    if($this->_db->insert('soort',array(
                        'Soort'     =>  $soort,
                        'IsEvent'   =>  '1',
                        'Datum'     =>  $datum
                    ))) {
                        return true;
                    }else{
                        return false;
                    }
                }else {
                    if($this->_db->insert('soort',array(
                        'Soort'     =>  $soort,
                        'IsEvent'   =>  '1',
                        'Datum'     =>  $datum
                    )));

                    $trainingsschemaclass = new Trainingsschema();
                    $trainingsschema = $trainingsschemaclass->getTrainingsschemaById($schema);

                    $trainingsschemaclass->createTTrainingsschema($soort,$trainingsschema->Afstand,$trainingsschema->Omschrijving,$trainingsschema->Niveau);
                    $id = $trainingsschemaclass->getTrainingsschemaByparameters($soort,$trainingsschema->Afstand,$trainingsschema->Niveau);

                    $workoutsclass = new Workout();
                    $workoutlijst = $workoutsclass->getWorkoutForTrainingSchema($schema);

                    foreach($workoutlijst as $workout){
                        $this->_db->get('onderdeel',array('Workout_ID','=',$workout->ID));

                        $tijd = array();
                        $soort = array();
                        foreach($this->_db->results() as $result){
                            array_push($tijd,$result->Tijd);
                            array_push($soort,$result->LoopSoorten_Soort);
                        }


                        var_dump($tijd);
                        echo '<br>';
                        var_dump($soort);

                        $workoutsclass->addWorkout($id->TraingId,$workout->Week,$workout->nummer,$tijd,$soort);
                    }

                    return true;
                }

            } else {
                return false;
            }
        }else{
            if($schema === "geen") {
                echo 'hallo 39';
                if ($this->_db->insert('soort', array(
                    'Soort' => $soort,
                    'IsEvent' => '0'
                ))
                ) {
                    return true;
                } else {
                    return false;
                }
            }else {
                if ($this->_db->insert('soort', array(
                    'Soort' => $soort,
                    'IsEvent' => '0'
                ))
                ) {
                    return true;
                } else {
                    return false;
                }
                $trainingsschemaclass = new Trainingsschema();
                $trainingsschema = $trainingsschemaclass->getTrainingsschemaById($schema);
                $trainingsschemaclass->createTTrainingsschema($soort,$trainingsschema->Afstand,$trainingsschema->Omschrijving,$trainingsschema->Niveau);
            }
        }
    }

    public function deleteSoort($soort){
        if($this->_db->delete('soort',array('Soort','=',$soort))) {
            return true;
        } else {
            return false;
        }
    }

    public function getRegioD($soort){
        if($this->_db->get('soort',array('Soort','=',$soort))){
            return $this->_db->first();
        }
    }

}

?>