<?php

class cVotingTable
{
  private $nreq;

  function __construct($nreq)
  {
    $this->nreq = $nreq;
  }

  function createHead()
  {
    echo "<table>
        		<thead>
        			<tr>
        				<th>Projekt</th>
        				<th>1. Wunsch</th>
                <?php
                  if($this->nreq > 1) echo "<th>2. Wunsch</th>" ;
                  if($this->nreq > 2) echo "<th>3. Wunsch</th>" ;
                ?>
                <th></th>
        			</tr>
        		</thead>
            <tbody>"
  }

  function createStudent1req($student)
  {
    
  }

}
