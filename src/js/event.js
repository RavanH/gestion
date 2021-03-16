import $ from 'jquery';
import 'bootstrap/js/dist/toast';

import {generateUrl} from "@nextcloud/router";
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/toast';
var baseUrl = generateUrl('/apps/gestion');


$(window).on("load", function(){
    $("#liveToast").toast({animation: true, delay: 4000});
});

$('body').on('click', '.menu', function(){
    $('#menu-'+this.dataset.menu).toggleClass('open');
});

$('body').on('click', '.editable', function(){
    $(this).attr('contenteditable', 'true');
});

$('body').on('blur', '.editable', function(){
    updateDB($(this).data('table'), $(this).data('column'), $(this).text(), $(this).data('id'));
    $(this).attr('contenteditable', 'false');
    $(this).removeAttr('contenteditable');
});

$('body').on('dblclick', '.selectableClient, .selectableClient_devis', function(){
    var id = $(this).data('id');
    $(this).text("");
    $(this).html('<select id="listClient">');
    listClient($('#listClient'), id);
});

$('body').on('click', '#listClient', function(){

    //Récupère les variables
    var id=$(this).find(':selected').data('id')
    var val=$(this).find(':selected').data('val')
    var column=$(this).find(':selected').data('column')
    var table=$(this).find(':selected').data('table')
    var el = $(this).parent();

    //Mise à jour de la table
    updateDB(table, column, val, id);

    //Modification spéciphique pour une catégorie
    if( el.get( 0 ).className == "selectableClient_devis"){
        getClient(val);
    }

    el.text($(this).val());

});

function getClient(id){
    var myData = {id: id,};
    $.ajax({
        url: baseUrl+'/client',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(myData)
    }).done(function (response, code) {
        $.each(JSON.parse(response), function(arrayID, myresp) {
            $("#entreprise").text(myresp.entreprise);
            $("#adresse").text(myresp.adresse);
            $("#mail").text(myresp.mail);
            $("#telephone").text(myresp.telephone);
            $("#siret").text(myresp.siret);
        });
    }).fail(function (response, code) {
        console.log(code);
    });
}

function listClient(lc, id){
    $.ajax({
        url: baseUrl+'/getClients',
        type: 'PROPFIND',
        contentType: 'application/json'
    }).done(function (response) {
        $.each(JSON.parse(response), function(arrayID, myresp) {
            lc.append('<option data-table="devis" data-column="id_client" data-val="'+myresp.id+'" data-id="'+ id +'">'+myresp.prenom + ' ' + myresp.nom + ' ' + '</option>');
        });
    }).fail(function (response, code) {
        console.log(code);
    });
}

function updateDB(table, column, data, id){
    var myData = {
        table: table,
        column: column,
        data: data,
        id: id,
    };

    $.ajax({
        url: baseUrl+'/client/update',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(myData)
    }).done(function (response, code) {
        $("#liveToast").toast('show');
    }).fail(function (response, code) {
        console.log(code);
    });
}

$('body').on('submit', '#clientinsert', function(e){
    e.preventDefault();
    var client = {
        nom: $('#nom').val(),
        prenom: $('#prenom').val(),
        siret: $('#siret').val(),
        entreprise: $('#entreprise').val(),
        telephone: $('#telephone').val(),
        mail: $('#mail').val(),
        adresse: $('#adresse').val()
    };
    
    $.ajax({
        url: baseUrl + '/client/insert',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(client)
    }).done(function (response) {
        console.log(response);
    }).fail(function (response, code) {
        error(response);
    });
});