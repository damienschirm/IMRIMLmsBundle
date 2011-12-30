<?php

/**
 *
 * @author Damien
 */
interface IChart {
    
    function numberOfUsers($numberOfEnrolment);
    function usersEnrolledInCourse(Controller $ctrl);
    function averageOfTimeSpentByUser(Controller $ctrl);
    function averageOfTimeSpentByCourse(Controller $ctrl);
    function listOfUsersNotHavingFinishedCourse(Controller $ctrl);
}

?>
