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

		for (i = 0; i < boxes.length; i++) {

			static_student_id = boxes[i].getElementsByTagName('div')[0].id;

			if(moved_student_id == static_student_id)
			{
				static_request = boxes[i].getElementsByTagName('div')[1].id;

				if(static_request != moved_request)
				{
					matched_position = rd.getPosition(boxes[i]);

					if(matched_position[1] == end_position[1])
					{
						window.alert("Student ist bereits in diesem Projekt vorhanden");
						move_before = true;
						break;
					}

					if(end_position[2] == matched_position[2])
					{
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
	};
};


// add onload event listener
if (window.addEventListener)
{
	window.addEventListener('load', redipsInit, false);
}
else if (window.attachEvent)
{
	window.attachEvent('onload', redipsInit);
}
