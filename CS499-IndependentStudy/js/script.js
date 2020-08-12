$(document).ready(function()
{
	/*
	*	This click event marks/unmarks the select all
	*	checkbox when its button container has been
	*	clicked then it marks/unmarks all the checkboxes
	*	in the table. 
	*/
	var checkAllBtn = $("#toggleBtn");
	var selectAllcheckbox = $("#selectAll");
	var checkboxes = $(".userCheckbox");
	
	checkAllBtn.click(function()
	{
		if (selectAllcheckbox.is(':checked'))
		{
			selectAllcheckbox.prop("checked", false);
			checkboxes.prop("checked", false);
		}
		
		else
		{
			selectAllcheckbox.prop("checked", true);
			checkboxes.prop("checked", true);
		}
	});
	
	/*
	*	Code for the modal dialog that is used to upload
	*	a new file
	*/
});