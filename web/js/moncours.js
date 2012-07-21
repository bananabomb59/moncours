/* javascript pour le site moncours.org */

function submitQuestionnaireForm(form,id){
	$('#questionnaire_choisi').val(id);
	$('#'+form).submit();
}