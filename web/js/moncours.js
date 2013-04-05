/* javascript pour le site moncours.org */

function submitQuestionnaireForm(form,id){
	$('#questionnaire_choisi').val(id);
	$('#'+form).submit();
}

function displayNouvelleAffectationEleve() {
	$("#form_valider_affectation").show();
	$("#submitbutton").show();
	$('#loading2').hide();
	
}
function cancelNouvelleAffectationEleve() {
	$("#form_valider_affectation").hide();
	$('#loading2').hide();
}

function submitNouvelleAffectationEleve() {
	$("#submitbutton").hide();
	$('#loading2').css("display","inline-block");
	$('#loading2').css("width","80px");
	$('#loading2').css("text-align","center");
}