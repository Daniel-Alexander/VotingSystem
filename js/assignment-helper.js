// INFO Script handles assignments: Based on REDIPS

var redipsInit,	rd = REDIPS.drag;

redipsInit = function ()
{
	rd.init();
	rd.shift.animation = true;

	var start_position;
	rd.event.moved = function ()
	{
		start_position = rd.getPosition(rd.obj);
	};

	rd.event.finish = function ()
	{
		var end_position = rd.getPosition(rd.obj);

		var table = rd.findParent('TABLE', rd.obj);

		if(table.id != "stack")
		{
			var boxes = document.getElementsByClassName("redips-drag");
			var moved_student_id = rd.obj.getElementsByTagName('div')[0].id;
			var moved_request = rd.obj.getElementsByTagName('div')[1].id;

			var move_id = -1;
			var move_pos = -1;
			var move_before = false;
			var switch_pos = false;

			var i = 0;
			var static_student_id;
			var static_request;
			var matched_position = {};

			for (i = 0; i < boxes.length; i++)
			{

				static_student_id = boxes[i].getElementsByTagName('div')[0].id;

				if(moved_student_id == static_student_id)
				{
					static_request = boxes[i].getElementsByTagName('div')[1].id;

					if(static_request != moved_request)
					{
						matched_position = rd.getPosition(boxes[i]);

						if(matched_position[1] == end_position[1] && matched_position[0] == end_position[0])
						{
							window.alert("Student ist bereits in diesem Projekt vorhanden");
							move_before = true;
							break;
						}

						if(end_position[2] == matched_position[2])
						{
							matched_position[0] = start_position[0];
							matched_position[2] = start_position[2];

							move_pos = true;
							move_id = boxes[i].id;
							move_pos = matched_position;
							// INFO No break here !! if row match comes later -> it is first prior
						}



					}
				}
			}


			if(move_before)
			{
				rd.moveObject({id: rd.obj.id, target: start_position});

			}
			else if (move_pos && move_id != -1 && move_pos != -1)
			{
				rd.moveObject({id: move_id, target: move_pos});
			}
		}
	};

	rd.event.dblClicked = function ()
	{
		var table = rd.findParent('TABLE', rd.obj);

		if(table.id != "stack")
		{
			var boxes = document.getElementsByClassName("redips-drag");
			var clicked_student_id = rd.obj.getElementsByTagName('div')[0].id;
			var clicked_request = rd.obj.getElementsByTagName('div')[1].id;

			var i = 0;

			for (i = 0; i < boxes.length; i++)
			{
				static_student_id = boxes[i].getElementsByTagName('div')[0].id;

				if(clicked_student_id == static_student_id)
				{
					static_request = boxes[i].getElementsByTagName('div')[1].id;

					if(static_request != clicked_request)
					{
						table = rd.findParent('TABLE', boxes[i]);

						if(table.id != "stack")
						{
							//window.alert("halloooo");
							var stack_position = {};
							stack_position[0] = 1;
							stack_position[1] = 1;
							stack_position[2] = 0;
							rd.moveObject({id: boxes[i].id, target: stack_position});
						}

					}
				}


			}
		}
	};


};

send_voting_table = function (finished)
{
	var boxes = document.getElementsByClassName("redips-drag");

	var rows = document.getElementsByClassName("project");

	var projects = [];
	for (i = 0; i < rows.length; i++)
	{
		projects.push(rows[i].id);
	}

	//.alert(projects);

	var students = [];
	var first = [];
	var second = [];
	var third = [];

	for (i = 0; i < boxes.length; i++)
	{
		var divs = boxes[i].getElementsByTagName('div');
		var std_id = divs[0].id;
		var req_id = divs[1].id;

		if(req_id == 'req_1')
		{
			students.push(std_id);
		}

		var position = rd.getPosition(boxes[i]);

		if(position[0] == 0)
		{
			if(position[2] == 1)
			{
				first.push([std_id,projects[position[1]-1]])
			}

			if(position[2] == 2)
			{
				second.push([std_id,projects[position[1]-1]])
			}

			if(position[2] == 3)
			{
				third.push([std_id,projects[position[1]-1]])
			}
		}

	}

	//window.alert(students);
	//window.alert(first);
	//window.alert(second);
	//window.alert(third);

	var assigned = [];

	for (i = 0; i < students.length; i++)
	{
		cur_id = students[i];
		assigned.push([cur_id,0,0,0]);

		for (j = 0; j < first.length; j++)
		{
			if(first[j][0] == cur_id)
			{
				assigned[i][1] = first[j][1];
			}
		}

		for (j = 0; j < second.length; j++)
		{
			if(second[j][0] == cur_id)
			{
				assigned[i][2] = second[j][1];
			}
		}

		for (j = 0; j < third.length; j++)
		{
			if(third[j][0] == cur_id)
			{
				assigned[i][3] = third[j][1];
			}
		}
	}

	var post_array = JSON.stringify(assigned);
	/*
	var str = "save_voting_table=1";
	for (i = 0; i < assigned.length; i++)
	{
		str = str+"&voting_table[]="+assigned[i][0]+"&voting_table[]="+assigned[i][1]+"&voting_table[]="+assigned[i][2]+"&voting_table[]="+assigned[i][3];
	}
	*/

	//str = "table[]=3&table[]=1&table[]=2"
	if(finished)
	{
		str = "update_voting_table=1&voting_finished=1&voting_table="+post_array;
	}
	else
	{
		str = "update_voting_table=1&voting_table="+post_array;
	}
	//

	/*
		2  1  2  0
		12 2  1  0
		13 4  2  0
	*/
	// TODO Browser kompatiblität prüfen

	var url = "redirect.php?page=voting";
	//var params = "new_teacher_name=Milli&new_teacher_mail=Millimail&new_teacher_pw=123&create_new_teacher=1";

	var xhr = new XMLHttpRequest();

	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(str);

}


// add onload event listener
if (window.addEventListener)
{
	window.addEventListener('load', redipsInit, false);
}
else if (window.attachEvent)
{
	window.attachEvent('onload', redipsInit);
}
