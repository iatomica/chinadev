function suggetion() { 
 
     $('#sug_input').keyup(function(e) {

         var formData = {
             'product_name' : $('input[name=title]').val()
         };

         if(formData['product_name'].length >= 1){

           // process the form
           $.ajax({
               type        : 'POST',
               url         : 'ajax.php',
               data        : formData,
               dataType    : 'json',
               encode      : true
           })
               .done(function(data) {
                   //console.log(data);
                   $('#result').html(data).fadeIn();
                   $('#result li').click(function() {

                     $('#sug_input').val($(this).text());
                     $('#result').fadeOut(500);

                   });

                   $("#sug_input").blur(function(){
                     $("#result").fadeOut(500);
                   });

               });

         } else {

           $("#result").hide();

         };

         e.preventDefault();
     });

 }
  $('#sug-form').submit(function(e) {
      var formData = {
          'p_name' : $('input[name=title]').val()
      };
        // process the form
        $.ajax({
            type        : 'POST',
            url         : 'ajax.php',
            data        : formData,
            dataType    : 'json',
            encode      : true
        })
            .done(function(data) {
                //console.log(data);
                $('#product_info').html(data).show();
                total();
               // $('.datePicker').datepicker('update', new Date());

            }).fail(function() {
                $('#product_info').html(data).show();
            });
      e.preventDefault();
  });
  function total(){
    $('#product_info input').change(function(e)  {
            var price = +$('input[name=price]').val() || 0;
            var qty   = +$('input[name=quantity]').val() || 0;
            var total = qty * price ;
                $('input[name=total]').val(total.toFixed(2));
    });
  }

 


  

$(document).ready(function() {

    //tooltip
    $('[data-toggle="tooltip"]').tooltip();

    $('.submenu-toggle').click(function () {
       $(this).parent().children('ul.submenu').toggle(200);
    });
    //suggetion for finding product names
   
/*
    $('.datepicker')
        .datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
        */
  });
$(document).ready(function() {

   $('#received-date-rg')
        .datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
 $('#po-eta')
        .datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
        
  $('#expiration-date-rg')
        .datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });


  });

$(document).ready(function() {

   $('#reportcompany-start-date')
        .datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
 $('#reportcompany-end-date')
        .datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });


  });




//Charge categories and subcategories

$(document).ready(function(){
    


    $('#product-categorie').val();
      
     recargarLista();

        
    $('#product-categorie').change(function(){
    
      recargarLista();
    
    });
  });
 function recargarLista(){
 var subcategorye = $('#subcategorye').val();
    
    var categorye = $('#product-categorie').val();
    $.ajax({
      type:'POST',
      url:'aj.php',
     // data: "dato=" + $('#product-categorie').val(),
     data:{categorye:categorye,subcategorye:subcategorye},
      dataType:'json',
      encode:true,
      
      success:function(r){
        $('#productsubcategorie').html(r);
        
        }
    });
  }


 
 // Maker alias insert
 
$(document).ready(function(){ 
var prid = $('#product-id').val();
var codevendor = $('#vendorcode').DataTable({
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"actioncodevendor.php",
    type:"POST",
    data:{action:'listVendorcode', prid:prid},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[2,3],
      "orderable":false,
    },
  ],
  "pageLength": 5
});

$('#addvendorcode').click(function(){
    $('#vendorcodeModal').modal('show');
    $('#vendorcodeForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add Maker alias");
    $('#action1').val('addVendorcode');
    $('#save').val('Add');
  });   




$("#vendorcode").on('click', '.update', function(){
  var vendorcodeId = $(this).attr("id");
  var action = 'getVendorcode';
  $.ajax({
    url:'actioncodevendor.php',
    method:"POST",
    data:{vendorcodeId:vendorcodeId, action:action},
    dataType:"json",
    success:function(data){
      $('#vendorcodeModal').modal('show');
      $('#vendorcodeId').val(data.id);
      $('#vendorcodeName').val(data.vendorcode);
      $('#vendorcodevendor').val(data.vendors_id);
      $('#vendorcodeProductid').val(data.products_id); 
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit Maker Alias");
      $('#action1').val('updateVendorcode');
      $('#save').val('Save');
    }
  })
 }); 

$("#vendorcodeModal").on('submit','#vendorcodeForm', function(event){
  event.preventDefault();
  $('#save').attr('disabled','disabled');
  var formData = $(this).serialize();
  $.ajax({
    url:"actioncodevendor.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#vendorcodeForm')[0].reset();
      $('#vendorcodeModal').modal('hide');        
      $('#save').attr('disabled', false);
      codevendor.ajax.reload();
    }
    })
  });   

$("#vendorcode").on('click', '.delete', function(){
  var vendor = $(this).attr("id");   
  var action = "vendorcodeDelete";
  if(confirm("Are you sure you want to delete this maker alias?")) {
    $.ajax({
      url:"actioncodevendor.php",
      method:"POST",
      data:{vendor:vendor, action:action},
      success:function(data){          
       codevendor.ajax.reload();
      }
    })
  } 
  else {
    return false;
  }
});

});

// Sales Confirmation insert



$(document).ready(function(){
  $("input[type='number']").change(function(){
var result = 0;
var result2 = '';
var result3 = '';
var uxb = $("input[id='po-code-uxb']").val();
var qty = $("input[id='po-code-qty']").val();
var result = qty / uxb;
    if(uxb && qty != ''){
      if(Number.isInteger(result))
           {
        $("#po-code-ctn").val(result);
          }
      else{
        $("#po-code-ctn").val(result2);
        $("#po-code-uxb").val(result3);
        $("#po-code-qty").val(result3);
        alert("CTN is not integer number");
        }
    }

    var nw = $("input[id='po-code-nw']").val();
    var gw = $("input[id='po-code-gw']").val();
    
  });
});





$(document).ready(function(){
    
    $('#po-productid').val();

    chargeitems() ;
      
     $('#po-productid').change(function(){
      chargeitems();
        
     });
  });
    
      
   function chargeitems(){
   var idproduct = $('#po-productid').val();
   
   $.ajax({
            url:'ajax_po_items.php',
            method:"POST",
            data:{idproduct:idproduct},
            dataType:"json",
            success:function(data){
              $('#po-code-description').val(vala);
              $('#po-code-fob').val(data.price);
              $('#po-code-cbm').val(data.cbm);
     
    
            }
        })


  }

  //Received goods insert maker

  $(document).ready(function(){ 
    chargeTps();

      $('#rg-code-name').keyup(function(){
            //var idcli = $('#client-rg').val(); 
          var query = $(this).val();
          var client = $('#rgclient').val();
          var vendor = $('#rgvendor').val();
           if(query != '')  
           {  
                $.ajax({  
                     url:"searchrgcodeclient.php",  
                     method:"POST",  
                     data:{query:query,client:client,vendor:vendor}, 
                     success:function(data)  
                     {  

                          $('#rg-clientcodename').fadeIn();
                          $('#rg-clientcodename').html(data);
                     }  
                });  
           } 

            


      });
       


              
 function validate_rg_items(){

 var prid = $('#rg-productid').val();   
 var rgid = $('#rgid').val();

     return $.ajax({
            url:'validate_items_rg.php', 
            method:"POST",
            data:{prid:prid,rgid:rgid},
            dataType:"json",
            success: function(data){
              console.log(data);
            }
            
        })


}



      $(document).on('click', 'li', function(){ 
           
             
            
          
           $('#rg-code-name').val($(this).text()); //aca tengo que hacer que agarre las cosas de purchaseorder, pero tengo que ver que lo saque del productid original
           $('#rg-productid').val($(this).val());
           

           $('#rg-clientcodename').fadeOut();
           var idproduct = $('#rg-productid').val();
           var clientcode = $('#rg-code-name').text();

            var validate_items = validate_rg_items();
               validate_items.success(function (data){
              var item_val = data;
             
              if (item_val === true){

             alert('This product is duplicated');
           
             $('#rg-code-name').val('');
             $('#rg-productid').val('');
              }
              else{

          $.ajax({
            url:'ajax_rg_items.php',
            method:"POST",
            data:{idproduct:idproduct},
            dataType:"json",
            success:function(data){
              $('#rg-code-description').val(data.desc_english);
              chargeTps();
     
    
            }
        })
        }
        })


});

  $("#rg-code-name").keydown(function(e) {
          //alert( "Handler for .focus() called." );
          var keyCode = e.keyCode || e.which; 

          if (keyCode === 9) {
           

           var firstli = $('#li-first').first();
           
            $(this).val(firstli.text());
            $('#rg-productid').val(firstli.val());
           var idproduct = $('#rg-productid').val();
           var clientcode = $('#rg-code-name').text();
           $('#rg-clientcodename').fadeOut();
           

           var validate_items = validate_rg_items();
               validate_items.success(function (data){
              var item_val = data;
             
              if (item_val === true){

             alert('This product is duplicated');
           
             $('#rg-code-name').val('');
             $('#rg-productid').val('');
              }
              else{

          $.ajax({
            url:'ajax_rg_items.php',
            method:"POST",
            data:{idproduct:idproduct},
            dataType:"json",
            success:function(data){
              $('#rg-code-description').val(data.desc_english);
              $('#rg-code-cbm').val(data.cbm);
              chargeTps();
     
     
    
            }
        })
        }
            })
           }
        
            
            });  


function chargeTps(tp){
 //var tps = $('#rg-tp').val();
    var tp_ids = tp;
    var idproduct = $('#rg-productid').val();
    var clientcode = $('#rg-code-name').val();
    var client = $('#client-rg').val();
    $.ajax({
      type:'POST',
      url:'aj_tp_rg.php',
     // data: "dato=" + $('#product-categorie').val(),
     data:{idproduct:idproduct,tp_ids:tp_ids,clientcode:clientcode,client:client},
      dataType:'json',
      encode:true,
      
      success:function(r){
        $('#rg-tps').html(r);
        
        }
    });
  }

//Revisar este codigo para terminar pedido maxi MR

  $(document).ready(function(){

  $('#rg-tps').change(function(){
      var idproducts = $('#rg-productid').val();
      var tp_id = $('#rg-tp').val();
        $.ajax({
      type:'POST',
      url:'aj_rg_price.php',
     // data: "dato=" + $('#product-categorie').val(),
     data:{idproducts:idproducts,tp_id:tp_id},
      dataType:'json',
      encode:true,
      
      success:function(data){
        $('#rg-code-fob').val('');
        $('#rg-code-fob').val(data.price_po);
        $('#rg-code-cbm').val(data.cbm_po);
        $('#rg-code-gw').val(data.gw);
        $('#rg-code-nw').val(data.nw);


        
        }
    })

        console.log(idproducts);
        console.log(tp_id);
    }); 
    
});
});
$(document).ready(function(){
  $("input[type='number']").change(function(){
var result = 0;
var result2 = '';
var result3 = '';
var uxb = $("input[id='rg-code-uxb']").val();
var qty = $("input[id='rg-code-qty']").val();
var result = qty / uxb;
    if(uxb && qty != ''){
      if(Number.isInteger(result))
           {
        $("#rg-code-ctn").val(result);
          }
      else{
        $("#rg-code-ctn").val(result2);
        $("#rg-code-uxb").val(result3);
        $("#rg-code-qty").val(result3);
        alert("CTN is not integer number");
        }
    }

    
    
    
  
  });
});



$("#rg-code-qty" ).focusout(function() {
          //alert( "Handler for .focus() called." );
           
              var validate_items_qty = validate_rg_items_qty();
              validate_items_qty.success(function (data){
              var item_val = data;
             
              if (item_val === true){
              $('#rg-code-qty').val('');
             alert('QTY EXCEED THE STOCK AVAILABLE');
            
           
             
             
              }
             
              
             

              
          })

         
            });

$("#rg-code-qty").keydown(function(e) {
        var keyCode = e.keyCode || e.which; 

          if (keyCode === 9) {
           
    
              var validate_items_qty = validate_rg_items_qty();
              validate_items_qty.success(function (data){
              var item_val = data;
             
              if (item_val === true){
                $('#rg-code-qty').val('');
             alert('QTY EXCEED THE STOCK AVAILABLE');
            
           
             
             
              }

              
             

              
          })
         
            
            

          }
          
              

            });


function validate_rg_items_qty(){

 var prid = $('#rg-productid').val();   
 var rgtp = $('#rg-tp').val();
 var rg_qty = $('#rg-code-qty').val();

     return $.ajax({
            url:'validate_rg_items_qty.php',
            method:"POST",
            data:{prid:prid,rgtp:rgtp,rg_qty:rg_qty},
            dataType:"json",
            success: function(data){
              console.log(data);
            }
            
        })


}


/*
  $(document).ready(function(){
    
    $('#rg-productid').val();

    chargeitems();
      chargeTps();
     $('#rg-productid').change(function(){
      chargeitems();
        chargeTps();
     });
  });
    
      
   function chargeitems(){
   var idproduct = $('#rg-productid').val();
   
   $.ajax({
            url:'ajax_rg_items.php',
            method:"POST",
            data:{idproduct:idproduct},
            dataType:"json",
            success:function(data){
              $('#rg-code-description').val(data.desc_english);
              $('#rg-code-fob').val(data.price);
              $('#rg-code-cbm').val(data.cbm);
              
    
            }
        })


  }
//received goods insert tp
  */
 

  //received goods process

$(document).ready(function(){ 
var rgid = $('#rgid').val();
var detailrg = $('#detail-rg').DataTable({
  dom: 'Bfrtip',
  buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11,12,13,14]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11,12,13,14]
                }
            },

           
            'colvis',
        'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "responsive": true,
  "ajax":{
    url:"actionreceivedgoods.php",
    type:"POST",
    data:{action:'listItemrg', rgid:rgid},
    dataType:"json"
  },
 
  "pageLength": 20,
  "footerCallback": function (tfoot, data, start, end, display) {
            var api = this.api();
            var p = api.column(14).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(14).footer()).html("Total: " + p.toFixed(2));
            var api = this.api();
            var p = api.column(13).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(13).footer()).html("Total: " + p.toFixed(2));

            var api = this.api();
            var p = api.column(12).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(12).footer()).html("Total: " + p.toFixed(2));

            var api = this.api();
            var p = api.column(11).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(11).footer()).html("Total: " + p.toFixed(4));


          },
});

$('#detail-rg tbody').on( 'click', 'tr',function(){
        $(this).toggleClass('selected');
    });



$('#additem-rg').click(function(){
     var qty = $('#rg-code-qty').val();
     var uxb = $('#rg-code-uxb').val();
    $('#rgModal').modal('show');
    $('#rgForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i>Add item");
    $('#action').val('addItemrg');
    //$('#action-po').val('processpo');
    $('#save').val('Add');

  });
    
    
$('#hide-totals-rg').click(function(){
    detailrg.column(10).visible(false);
    detailrg.column(11).visible(false);
    detailrg.column(12).visible(false);


   });

$('#show-totals-rg').click(function(){
    detailrg.column(10).visible(true);
    detailrg.column(11).visible(true);
    detailrg.column(12).visible(true);

   });
$("#detail-rg").on('click', '.update', function(){
  var tp_ids =  $('#rg-tp-hidden').val();
  var item2 = $(this).attr("id");
  var s = $('#rg-id').val();
  var action = 'getItemrg';
  $.ajax({
    url:'actionreceivedgoods.php',
    method:"POST",
    data:{item2:item2, action:action},
    dataType:"json",
    success:function(data){
      $('#rgModal').modal('show');
      $('#rg-id').val(data.id);
      $('#rg-code-name').val(data.clientcode_rg);
      $('#rg-productid').val(data.products_id);
      $('#rg-code-description').val(data.desc_pr_rg);
      $('#rg-code-uxb').val(data.uxb_rg);
      $('#rg-code-qty').val(data.qty_rg);
      $('#rg-code-fob').val(data.price_rg);
      $('#rg-code-ctn').val(data.ctn);
      $('#rg-code-cbm').val(data.cbm_rg);
      $('#rg-code-gw').val(data.gw);
      $('#rg-code-nw').val(data.nw);
      $('#rg-tp-hidden').val(data.purchaseorder_id);
      $('#rg-qty-hidden').val(data.qty_rg);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit item");
      //$('#action-po').val('processpoUpdate');
      $('#action').val('updateItemrg');
      $('#save').val('Save');
    
      chargeTps(data.purchaseorder_id);
      
    }
  })
 });



$("#rgModal").on('submit','#rgForm', function(event){
  event.preventDefault();
  $('#save').attr('disabled','disabled');
  var formData = $(this).serialize();

  $.ajax({
    url:"actionreceivedgoods.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#rgForm')[0].reset();
      $('#rgModal').modal('hide');        
      $('#save').attr('disabled', false);
      detailrg.ajax.reload();
    }
    })


  });
 $("#detail-rg").on('click', '.delete', function(){
  var item = $(this).attr("id");   
  var action = "ItemrgDelete";
  if(confirm("Are you sure you want to delete this code?")) {
    $.ajax({
      url:"actionreceivedgoods.php",
      method:"POST",
      data:{item:item, action:action},
      success:function(data){          
       detailrg.ajax.reload();
      }
    })
  } 
  else {
    return false;
  }
});



});

//Sales confimation process
  
$(document).ready(function(){ 
var poid = $('#poid').val();
var detailpo = $('#detail-po').DataTable({
  dom: 'Bfrtip',
  buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11,12,13,14,15,16]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 5, 6, 7, 8, 9, 10, 11,12,13,14,15,16]
                }
            },
           
            'colvis',
        'print'
        ],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
   lengthMenu:[ 3, 5, 10, -1 ],
  //"ordering":false, 
  //"searching":false,
  "responsive": true,
  "ajax":{
    url:"actionpurchaseorder.php",
    type:"POST",
    data:{action:'listItempo', poid:poid},
    dataType:"json"
  },
 
  "pageLength": 20,
  "footerCallback": function (tfoot, data, start, end, display) {
            var api = this.api();
            var p = api.column(14).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(14).footer()).html("Total: " + p.toFixed(2));
            var api = this.api();
            var p = api.column(13).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(13).footer()).html("Total: " + p.toFixed(2));

            var api = this.api();
            var p = api.column(12).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(12).footer()).html("Total: " + p.toFixed(2));

            var api = this.api();
            var p = api.column(11).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(11).footer()).html("Total: " + p.toFixed(4));


          },


});


$('#detail-po tbody').on( 'click', 'tr',function(){
        $(this).toggleClass('selected');
    });





$(document).ready(function(){ 
      $('#po-code-name').keyup(function(e){
            var idcli = $('#client-po').val(); 
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"searchcodeclient.php",  
                     method:"POST",  
                     data:{query:query,idcli:idcli},  
                     success:function(data)  
                     {  
                          $('#po-clientcodename').fadeIn();  
                          $('#po-clientcodename').html(data);  
                     }  
                });  
           }
           var keyCode = e.keyCode || e.which; 

          if (keyCode == 9) {

          }


      });  
      $(document).on('click', 'li', function(e){  
           

           $('#po-code-name').val($(this).text());
           $('#po-productid').val($(this).val());
           $('#po-clientcodename').fadeOut();
           var idproduct = $('#po-productid').val();
           var validate_items = validate_po_items();
               validate_items.success(function (data){
              var item_val = data;
             
              if (item_val === true){

             alert('This product is duplicated');
           
             $('#po-code-name').val('');
             $('#po-productid').val('');
              }
              else{
               $.ajax({
                url:'ajax_po_items.php',
                method:"POST",
                data:{idproduct:idproduct},
                dataType:"json",
                success:function(data){          
            
              
              $('#po-code-description').val(data.desc_english);
              $('#po-code-fob').val(data.price);
              $('#po-code-cbm').val(data.cbm);
              $('#po-code-uxb').val(data.uxb);
              $('#po-code-nw').val(data.netweight);
              $('#po-code-gw').val(data.grossweight);
              $('#po-code-volume').val(data.volume);

                    }
              })
               e.preventDefault();
              }
          })
         
      

});

    $("#po-code-name").keydown(function(e) {
          //alert( "Handler for .focus() called." );
           
             var keyCode = e.keyCode || e.which; 

          if (keyCode === 9) {

          
           var firstli = $('#li-first').first();
           
            $(this).val(firstli.text());
            $('#po-clientcodename').fadeOut();
            $('#po-productid').val(firstli.val());
           var idproduct = $('#po-productid').val();
           var validate_items = validate_po_items();
               validate_items.success(function (data){
              var item_val = data;
             
              if (item_val === true){

             alert('This product is duplicated');
           
             $('#po-code-name').val('');
             $('#po-productid').val('');
              }
              else{
               $.ajax({
                url:'ajax_po_items.php',
                method:"POST",
                data:{idproduct:idproduct},
                dataType:"json",
                success:function(data){          
            
              
              $('#po-code-description').val(data.desc_english);
              $('#po-code-fob').val(data.price);
              $('#po-code-cbm').val(data.cbm);
              $('#po-code-uxb').val(data.uxb);
              $('#po-code-nw').val(data.netweight);
              $('#po-code-gw').val(data.grossweight);
              $('#po-code-volume').val(data.volume);

                    }
              })

              }


          })
      
            
           } 
   });   
  
});



function validate_po_items(){

 var prid = $('#po-productid').val();   
 var poid = $('#poid').val();

     return $.ajax({
            url:'ajax_validate_po_items.php', 
            method:"POST",
            data:{prid:prid,poid:poid},
            dataType:"json",
            success: function(data){
              console.log(data);
            }
            
        })


}



$('#additem-po').click(function(){
     var qty = $('#po-code-qty').val();
     //var uxb = $('#po-code-uxb').val();
    $('#poModal').modal('show');
    $('#poForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i>Add item");
    $('#action').val('addItempo');
    $('#save').val('Add');
  });
  
$('#hide-totals-po').click(function(){
    detailpo.column(10).visible(false);
    detailpo.column(11).visible(false);
    detailpo.column(12).visible(false);


   });

$('#show-totals-po').click(function(){
    detailpo.column(10).visible(true);
    detailpo.column(11).visible(true);
    detailpo.column(12).visible(true);


   });

$("#detail-po").on('click', '.update', function(){
  var item1 = $(this).attr("id");
  var s = $('#po-id').val();
  var action = 'getItempo';
  $.ajax({
    url:'actionpurchaseorder.php',
    method:"POST",
    data:{item1:item1, action:action},
    dataType:"json",
    success:function(data){
      $('#poModal').modal('show');
      $('#po-id').val(data.id);
      $('#po-code-name').val(data.clientcode_po);
      $('#po-productid').val(data.products_id);
      $('#po-code-description').val(data.desc_pr_po);
      $('#po-code-uxb').val(data.uxb_po);
      $('#po-code-qty').val(data.qty_po);
      $('#po-code-fob').val(data.price_po);
      $('#po-code-ctn').val(data.ctn);
      $('#po-code-cbm').val(data.cbm_po);
      $('#po-code-gw').val(data.gw);
      $('#po-code-nw').val(data.nw);
      $('#po-code-volume').val(data.volume);
      $('#po-eta').val(data.eta);
      $('#po-code-qty-hidden').val(data.qty_po);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit item");
      $('#action').val('updateItempo');
      $('#save').val('Save');
    }
  })
 }); 

$("#poModal").on('submit','#poForm', function(event){
  event.preventDefault();
  $('#save').attr('disabled','disabled');
  var formData = $(this).serialize();

  $.ajax({
    url:"actionpurchaseorder.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#poForm')[0].reset();
      $('#poModal').modal('hide');        
      $('#save').attr('disabled', false);
      detailpo.ajax.reload();
    }
    })


  });
 $("#detail-po").on('click', '.status-item-po', function(){
  var item_change = $(this).attr("id");

  var action = "changeStatusItempo";
  if(confirm("Are you sure you want to delete this code?")) {
    $.ajax({
      url:"actionpurchaseorder.php",
      method:"POST",
      data:{item_change:item_change, action:action},
      success:function(data){          
       detailpo.ajax.reload();
      }
    })
  } 
  else {
    return false;
  }
});



 $("#detail-po").on('click', '.delete', function(){
  var item = $(this).attr("id");   
  var action = "ItempoDelete";
  if(confirm("Are you sure you want to change status?")) {
    $.ajax({
      url:"actionpurchaseorder.php",
      method:"POST",
      data:{item:item, action:action},
      success:function(data){          
       detailpo.ajax.reload();
      }
    })
  } 
  else {
    return false;
  }
});

});

//Produts table info


$(document).ready(function(){
  
var productinfo = $('#productss-info').DataTable({
  
  dom: 'Bfrtip',
   
"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "all"]],
  buttons: [ 
              'pageLength',
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0,1,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,1,17,18],
                      format: {
            body: function (data, row, column, node) {
              if (column === 1) { // Check if it's the column for image
                // Extract the image URL from the HTML
                var imgURL = $(data).attr('src');
                return imgURL; // Return the image URL as the copied value
              }
              return data; // Return the original data for other columns
                 }

                }
            },
            
                },
                 {
                        

                        text: 'Excel with Images',
                            
                            columns: [ 0,1,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,1,17,18],
                              
                                
                    
                   

                             action: function (e, dt, node, config) {
                                

                               $.ajax({
                                url: 'save_excel.php',
                                method: 'POST',
                                data: { data: JSON.stringify(dt.data().toArray()) },
                                success: function(response) {
                                    // Handle the response from the server
                                    if (response === 'success') {
                                       var downloadLink = document.createElement('a');
                                         downloadLink.href = 'exports/exported_products.xls';
                                         downloadLink.download = 'exported_products.xls';
                                         downloadLink.click();
                                            
                                    } else {
                                        // An error occurred while saving the file
                                        alert('Error saving Excel file!');
                                    }
                                },
                                error: function() {
                                    // An error occurred during the AJAX request
                                    alert('Error occurred during AJAX request!');
                                }
                            });

                        
                           
                        }


                            

                      
                    },

                {
                extend: 'excelHtml5',
                  
                     //data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA5IA…jxvXlLg/dYBEREREREWv7f1xZE9DE4CqpAAAAAElFTkSuQmCC
                exportOptions: {
                   stripHtml: false,
                    columns: [0,1,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,1,17,18],
                    format: {
            body: function (data, row, column, node) {
              if (column === 1) { // Check if it's the column for image
                // Extract the image URL from the HTML
                var imgURL = $(data).attr('src');
                return '=HYPERLINK("' + imgURL + '")'; // Return hyperlink to the image URL
              }
              return data; // Return the original data for other columns
            }
                    
                    
                    },


          
                },
            },
           
            
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 0,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,1,17,18]
                }
            },
            'colvis', 
             {
                extend: 'pdfHtml5',
                 
                exportOptions: {
                 
                    columns: [0,1,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,1,17,18],
                    
                    
                    },


                },
        ],

 
  //"lengthChange": false,
  "processing":true,
  "paging": true,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "responsive": true,
 
  "ajax":{
    url:"product_infoaction.php",
    type:"POST",
    data:{action:'listProduct1'},
    dataType:"json",
     
     
  },
  "columnDefs":[
    {
      "targets":[1],
      "orderable":false,
    },
  ],
  "pageLength": 10,

 



});




productinfo.column(4).visible(false);
productinfo.column(5).visible(false);
productinfo.column(6).visible(false);
productinfo.column(7).visible(false);
productinfo.column(8).visible(false);
productinfo.column(9).visible(false);
productinfo.column(10).visible(false);
productinfo.column(11).visible(false);
productinfo.column(12).visible(false);
productinfo.column(13).visible(false);
productinfo.column(14).visible(false);
productinfo.column(15).visible(false);
productinfo.column(16).visible(false);
productinfo.column(17).visible(false);
productinfo.column(18).visible(false);
productinfo.column(19).visible(false);
productinfo.column(20).visible(false);
productinfo.column(21).visible(false);
productinfo.column(22).visible(false);
 


$('#productsCustomerSelect').change(function(){
$('#productss-info').DataTable().destroy();
var filter = $('#productsCustomerSelect').val();
var productinfo = $('#productss-info').DataTable({
  
  dom: 'Blfrtip',
   
"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "all"]],
  buttons:  [
            'pageLength',
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0,4,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,1,17,18]
                }
            },
            {
                        

                        text: 'Excel with Images',
                            
                            columns: [ 0,1,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,1,17,18],
                              
                                
                    
                   

                             action: function (e, dt, node, config) {
                                

                               $.ajax({
                                url: 'save_excel.php',
                                method: 'POST',
                                data: { data: JSON.stringify(dt.data().toArray()) },
                                success: function(response) {
                                    // Handle the response from the server
                                    if (response === 'success') {
                                       var downloadLink = document.createElement('a');
                                         downloadLink.href = 'exports/exported_products.xls';
                                         downloadLink.download = 'exported_products.xls';
                                         downloadLink.click();
                                            
                                    } else {
                                        // An error occurred while saving the file
                                        alert('Error saving Excel file!');
                                    }
                                },
                                error: function() {
                                    // An error occurred during the AJAX request
                                    alert('Error occurred during AJAX request!');
                                }
                            });

                        
                           
                        }


                            

                      
                    },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0,4,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,1,17,18]
                }
            },

            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0,4,2,11,14,3,5,6,7,9,19,15,16,8,12,13,10,20,21,22,1,17,18]
                }
            },
            'colvis'
        ],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "responsive": true,
  "ajax":{
    url:"product_infoaction.php",
    type:"POST",
    data:function(d){
      d.action = 'listProduct';
      d.filter = $('#productsCustomerSelect').val();
    },
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": (filter === '0' ? 10 : -1)



});
// Mostrar Customer Alias (columna 4) cuando hay filtro aplicado
productinfo.column(4).visible(true);
productinfo.column(5).visible(false);
productinfo.column(6).visible(false);
productinfo.column(7).visible(false);
productinfo.column(8).visible(false);
productinfo.column(9).visible(false);
productinfo.column(10).visible(false);
productinfo.column(11).visible(false);
productinfo.column(12).visible(false);
productinfo.column(13).visible(false);
productinfo.column(14).visible(false);
productinfo.column(15).visible(false);
productinfo.column(16).visible(false);
productinfo.column(17).visible(false);
productinfo.column(18).visible(false);
productinfo.column(19).visible(false);
productinfo.column(20).visible(false);
productinfo.column(21).visible(false);
productinfo.column(22).visible(false);
});

 });




/*

$(document).ready(function(){


$("#productss-info").on('click', '.editproduct', function(){

var item = $(this).attr("id");
$(this).attr('href', 'edit_product.php?id='+item);

  
      $("#tabProduct").tabs("disable");            


  //$("#tabProduct").tabs("disable");


 });
});

*/



function imagen(id){
  
var modal = document.getElementById("myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById(id);
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");

  modal.style.display = "block";
  modalImg.src = img.src;
  captionText.innerHTML = img.src;


// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}



}

//Receiver goods table info
$(document).ready(function(){

//var prid = $('#product-id').val();
var rginfo = $('#rg-info').DataTable({
   dom: 'Blfrtip',
   "pageLength" : 50,
   buttons: ['pageLength','copy', 'csv', 'excel', 'pdf', 'print', 'colvis'],
  
 
"lengthMenu": [[50, 30, 20, -1], [50, 30, 20, "All"]],
 
  "lengthChange": false,
  "processing":true,
  //"paging": true,
  "serverSide":true,
  "order":[],
 
 // "ordering":false, 
  //"searching":false,
  "ajax":{
    url:"receivedgoods_infoaction.php",
    type:"POST",
    data:{action:'listRg'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[2,3],
      "orderable":false,

    },
  ],
   
   
 

});
rginfo.column(3).visible(false);
rginfo.column(5).visible(false);
rginfo.column(6).visible(false);

});

//Sales confirmation table info 

$(document).ready(function(){
  var infopo = $('#pos1-info').DataTable({
  dom: 'Bfrtip',
  "pageLength" : 50,
   buttons: ['pageLength','copy', 'csv', 'excel', 'pdf', 'print', 'colvis'],
  
 
"lengthMenu": [[50, 30, 20, -1], [50, 30, 20, "All"]],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"purchaseorder_infoaction.php",
    type:"POST",
    data:{action:'listPo'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[2,3],
      "orderable":false,
    },
  ],
  

});
  
//var prid = $('#product-id').val();
$('#po-filter').change(function(){
$('#pos1-info').DataTable().destroy();
var filter = $('#po-filter').val();
var infopo = $('#pos1-info').DataTable({
dom: 'Bfrtip',
buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"purchaseorder_infoaction.php",
    type:"POST",
    data:{action:'listPo',filter:filter},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[2,3],
      "orderable":false,
    },
  ],
  "pageLength": 30

});


  });
/*
$('#po-filter').change(function(){
      var filter = $('#po-filter').val();
      $.ajax({
      url:"purchaseorder_infoaction.php",
      method:"POST",
      data:{filter:filter, action:'listPo'},
      success:function(data){          
       infopo.ajax.reload();
      }
    })
        
     });
*/
});

// Maker table info

$(document).ready(function(){
//var prid = $('#product-id').val();
var vendorinfo = $('#vendor-info').DataTable({
  dom: 'Bfrtip',
  buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, 1,4,9,10,]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1,4,9,10,]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1,4,9,10]
                }
            },
            'colvis'
        ],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"vendor_infoaction.php",
    type:"POST",
    data:{action:'listVendor'},
    dataType:"json"
  },
  
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 10
});
vendorinfo.column(2).visible(false);
vendorinfo.column(5).visible(false);
vendorinfo.column(7).visible(false);
vendorinfo.column(8).visible(false);
vendorinfo.column(9).visible(false);
vendorinfo.column(10).visible(false);
vendorinfo.column(11).visible(false);
vendorinfo.column(12).visible(false);


});


//Customer table info

$(document).ready(function(){
//var prid = $('#product-id').val();
var clientinfo = $('#client-info').DataTable({
  dom: 'Bfrtip',
  buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0,1,2,3]
                }
            },
            {
                extend: 'excelHtml5', footer: true,
                exportOptions: {
                    columns: [0,1,2,3]
                }
            },
            
        'print'
        ],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"client_infoaction.php",
    type:"POST",
    data:{action:'listClient'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[2,3],
      "orderable":false,
    },
  ],
  "pageLength": 10
});


});

//View stock table




$(document).ready(function(){
//var prid = $('#product-id').val();
var viewstock = $('#viewstock-info').DataTable({
  "lengthMenu": [[200, 75, 50, -1], [100, 75, 50, "all"]],
   "pageLength": 200,
  dom: 'Bfrtip',
  buttons: ['pageLength','copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"viewstock_infoaction.php",
    type:"POST",
    data:{action:'listStock'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[2,3],
      "orderable":false,
    },
  ],
  
});


});

//stock movements info table

$(document).ready(function(){
//var prid = $('#product-id').val();
var sminfo = $('#sm-info').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"stockmovement_infoaction.php",
    type:"POST",
    data:{action:'listSm'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[2,3],
      "orderable":false,
    },
  ],
  "pageLength": 30
});


});

$(document).ready(function(){
  


$('#hidedeposit').hide();
$('#hidedeposito').hide();
  /*

$('#hidedeposit').hide();


  var movtype = $('#movement-sm').val();

$('#movement-sm').change(function(){
    
      if (this.value === '1'){
        $('#hidedeposit').hide();
      }
      else
      {
        $('#hidedeposit').show();
      }
    
    })

    */
 });


$(document).ready(function(){

  var paymentto = $('#paymentto-pay').val();

$('#paymentto-pay').change(function(){
    
      if (this.value === '2'){
        $('#hide-payment-cli-sa').show();
        $('#hide-payment-am-ob').show();
        $('#hide-payment-button').show();
        $('#hide-payment-ma-rg').hide();
       
        
      }
      if (this.value === '1'){
        $('#hide-payment-ma-rg').show();
        $('#hide-payment-am-ob').show();
        $('#hide-payment-button').show();
        $('#hide-payment-cli-sa').hide();
        
        
      }
    
    })
 });

$(document).ready(function(){

  var dnmentto = $('#debitnoteto-dn').val();

$('#debitnoteto-dn').change(function(){
    
      if (this.value === '2'){
        $('#hide-debitnote-cli-sa').show();
        $('#hide-debitnote-am-ob').show();
        $('#hide-debitnote-button').show();
        $('#hide-debitnote-ma-rg').hide();
       
        
      }
      if (this.value === '1'){
        $('#hide-debitnote-ma-rg').show();
        $('#hide-debitnote-am-ob').show();
        $('#hide-debitnote-button').show();
        $('#hide-debitnote-cli-sa').hide();
        
        
      }
    
    })
 });


$(document).ready(function(){

  var cnmentto = $('#creditnoteto-cn').val();

$('#creditnoteto-cn').change(function(){
    
      if (this.value === '2'){
        $('#hide-creditnote-cli-sa').show();
        $('#hide-creditnote-am-ob').show();
        $('#hide-creditnote-button').show();
        $('#hide-creditnote-ma-rg').hide();
       
        
      }
      if (this.value === '1'){
        $('#hide-creditnote-ma-rg').show();
        $('#hide-creditnote-am-ob').show();
        $('#hide-creditnote-button').show();
        $('#hide-creditnote-cli-sa').hide();
        
        
      }
    
    })
 });

$(document).ready(function(){

$('#reportcato-ca').change(function(){
  var blank = '';
    $('#report-check-all').prop('checked', true);
  //$('.myCheckbox').prop('checked', false);
      if (this.value === '1'){
        $('#hide-reportca-ma').show();
        $('#hide-reportca-filters').show();
        
        $('#hide-reportca-button').show();
        $('#hide-reportca-cli').hide();
        $('#client-ca').val("");
        $('#exp_1').text("Expiration");

        
      }
      if (this.value === '2'){
        $('#hide-reportca-cli').show();
       $('#hide-reportca-filters').show();
        
        $('#hide-reportca-button').show();
        $('#hide-reportca-ma').hide();
        $('#vendor-ca').val("");
         $('#exp_1').text("Date")
        
      }
    
    })

  $('#client-ca').change(function(){
      if (this.value === '0'){
        $('#exp_0').text("id");
        $('#exp_1').text("Customer");
        $('#exp_2').text("Balance");
        $('#exp_3').text("Taxpayer");
        $('#exp_4').text("Currency");
       

      }
      else{
        $('#exp_0').text("id");
        $('#exp_1').text("Date");
        $('#exp_2').text("Type");
        $('#exp_3').text("Number");
        $('#exp_4').text("Destination");
      }

  })

  $('#vendor-ca').change(function(){
    if (this.value === '0'){
        $('#exp_0').text("id");
        $('#exp_1').text("Supplier");
         $('#exp_2').text("Balance");
        $('#exp_3').text("Bank");
        $('#exp_4').text("Bank Account");
        $('#exp_5').text("Beneficiary Name");
         $('#exp_6').text("Currency");

      }
      else{
        $('#exp_0').text("id");
        $('#exp_1').text("Expiration");
        $('#exp_2').text("Type");
        $('#exp_3').text("Number");
        $('#exp_4').text("Destination");
         $('#exp_5').text("Debit");
         $('#exp_6').text("Credit");
      }
    
  })

$('#reportmovdate-ca').change(function(){
    if (this.value === '1'){
      $('#hide-reportca-filter-date').hide();
    }

    if (this.value === '2'){
      $('#hide-reportca-filter-date').show();
    }
   })

$('#report-check-payment').change(function(){

      if(this.checked){
        $('#report-check-all').prop('checked', false);
      }

   })
$('#report-check-debitnote').change(function(){

      if(this.checked){
        $('#report-check-all').prop('checked', false);
      }

   })
$('#report-check-creditnote').change(function(){

      if(this.checked){
        $('#report-check-all').prop('checked', false);
      }

   })
$('#report-check-rg').change(function(){

      if(this.checked){
        $('#report-check-all').prop('checked', false);
      }

   })
$('#report-check-all').change(function(){

      if(this.checked){
        $('#report-check-payment').prop('checked', false);
        $('#report-check-debitnote').prop('checked', false);
        $('#report-check-creditnote').prop('checked', false);
        $('#report-check-rg').prop('checked', false);
         $('#report-check-sales').prop('checked', false);
      }

   })


$('#report-check-sales').change(function(){

      if(this.checked){
        $('#report-check-all').prop('checked', false);
      }

   })
 $('#hide-reportca-filters').change(function(){
    
    if (this.value === '1'){
      $('#hide-reportca-filter-date').hide();
    }

    if (this.value === '2'){
      $('#hide-reportca-filter-date').show();
    }
    })

 });





  


//stock movements detail insert

$(document).ready(function(){ 
var smid = $('#smid').val();
var detailsm = $('#detail-sm').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  "paging": false,
  "serverSide":true,
  "order":[],
  "ordering":false, 
  "searching":false,
  "responsive": true,
  "ajax":{
    url:"actionstockmovement.php",
    type:"POST",
    data:{action:'listItemsm', smid:smid},
    dataType:"json"
  },
 
  "pageLength": 100,
  


});


$('#detail-sm tbody').on( 'click', 'tr',function(){
        $(this).toggleClass('selected');
    });





$(document).ready(function(){ 
      $('#sm-code-name').keyup(function(){
           var cli = $('#client-editstock').val();
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"searchclient_sm.php",  
                     method:"POST",  
                     data:{query:query,cli:cli},
                     success:function(data)  
                     {  
                          $('#sm-clientcodename').fadeIn();  
                          $('#sm-clientcodename').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           

           $('#sm-code-name').val($(this).text());
           $('#sm-productid').val($(this).val());
           $('#sm-clientcodename').fadeOut();
           var idproduct = $('#sm-productid').val();
           var clientcode = $('#sm-code-name').text();
           var validate_items_sm = validate_sm_items();
           validate_items_sm.success(function (data) {
              var item_val_sm = data;
             
              if (item_val_sm === true){

             alert('This product is duplicated');
           
             $('#sm-code-name').val('');
             $('#sm-productid').val('');
              }
               else{
          $.ajax({
            url:'ajax_rg_items.php',
            method:"POST",
            data:{idproduct:idproduct},
            dataType:"json",
            success:function(data){
              $('#sm-code-description').val(data.desc_english);
              
      
                }
              })

              }
          })
         
      

});

     
    
});

function validate_sm_items(){

 var prid = $('#sm-productid').val();   
 var smid = $('#smid').val();

     return $.ajax({
            url:'ajax_validate_sm_items.php',
            method:"POST",
            data:{prid:prid,smid:smid},
            dataType:"json",
            success: function(data){
              console.log(data);
            }
            
        })


}
    







$('#additem-sm').click(function(){
     var qty = $('#sm-code-qty').val();
     var uxb = $('#sm-code-uxb').val();
    $('#smModal').modal('show');
    $('#smForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i>Add item");
    $('#action').val('addItemsm');
    $('#save').val('Add');
  });
  

$("#detail-sm").on('click', '.update', function(){
  var item1 = $(this).attr("id");
  var s = $('#sm-id').val();
  var action = 'getItemsm';
  $.ajax({
    url:'actionstockmovement.php',
    method:"POST",
    data:{item1:item1, action:action},
    dataType:"json",
    success:function(data){
      $('#smModal').modal('show');
      $('#sm-ids').val(data.id);
      $('#sm-code-name').val(data.clientcode_sm);
      $('#sm-productid').val(data.products_id);
      $('#sm-code-description').val(data.desc_pr_sm);
      $('#sm-code-qty').val(data.qty_sm);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit item");
      $('#action').val('updateItemsm');
      $('#save').val('Save');
    }
  })
 }); 



$("#smModal").on('submit','#smForm', function(event){
  event.preventDefault();
  $('#save').attr('disabled','disabled');
  var formData = $(this).serialize();

  $.ajax({
    url:"actionstockmovement.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#smForm')[0].reset();
      $('#smModal').modal('hide');        
      $('#save').attr('disabled', false);
      detailsm.ajax.reload();
    }
    })


  });
 $("#detail-sm").on('click', '.delete', function(){
  var item = $(this).attr("id");   
  var action = "ItemsmDelete";
  if(confirm("Are you sure you want to delete this item?")) {
    $.ajax({
      url:"actionstockmovement.php",
      method:"POST",
      data:{item:item, action:action},
      success:function(data){          
       detailsm.ajax.reload();
      }
    })
  } 
  else {
    return false;
  }
});

});

 $(document).ready(function(){ 
     $('#sales-pay').keyup(function(e){
            //var idcli = $('#client-rg').val(); 
           var query = $(this).val();
           var client = $('#client-pay').val();
            
           if(query != '')  
           {  
                $.ajax({  
                     url:"seachsales.php",  
                     method:"POST",  
                     data:{query:query,client:client},  
                     success:function(data)  
                     {  
                          $('#pay-sales').fadeIn();  
                          $('#pay-sales').html(data);  
                     }  
                });  
           } 


           $("list-rg-ca-items li:first-child").css("background-color", "yellow");
           

           var keyCode = e.keyCode || e.which; 

          if (keyCode == 113) { 
          e.preventDefault(); 
            
            
            } 
           
            
      });
       
      $('#client-pay').change(function(){
      var blank = '';
      $('#sales-pay').val(blank);
      $('#pay-id-sales').val(blank);
      
    
    })


      $(document).on('click', 'li', function(){ 
           

          $('#sales-pay').val($(this).text());
          $('#pay-id-sales').val($(this).val());
         
          $('#pay-sales').fadeOut();
          
          //$('#receivedgoods-pay').focusout(alert( "Handler for .focus() called."));
          

});

      $( "#sales-pay" ).focusout(function() {
          //alert( "Handler for .focus() called." );
           

           var a = $('#li-first').first();
           console.log(a);
            $(this).val(a.text());
            $('#pay-id-sales').val(a.val());
         
          $('#pay-sales').fadeOut();
            });
      
        
    
 

     
    
});

  $(document).ready(function(){ 
     $('#receivedgoods-pay').keyup(function(e){
            //var idcli = $('#client-rg').val(); 
           var query = $(this).val();
           var vendor = $('#vendor-pay').val();
            
           if(query != '')  
           {  
                $.ajax({  
                     url:"searchreceivedgoods.php",  
                     method:"POST",  
                     data:{query:query,vendor:vendor},  
                     success:function(data)  
                     {  
                          $('#pay-receivedgoods').fadeIn();  
                          $('#pay-receivedgoods').html(data);  
                     }  
                });  
           } 


           $("list-rg-ca-items li:first-child").css("background-color", "yellow");
           

           var keyCode = e.keyCode || e.which; 

          if (keyCode == 113) { 
          e.preventDefault(); 
            
            
            } 
           
            
      });
       
      $('#vendor-pay').change(function(){
      var blank = '';
      $('#receivedgoods-pay').val(blank);
      $('#pay-id-receivedgoods').val(blank);
      
    
    })


      $(document).on('click', 'li', function(){ 
           

          $('#receivedgoods-pay').val($(this).text());
          $('#pay-id-receivedgoods').val($(this).val());
         
          $('#pay-receivedgoods').fadeOut();
          
          //$('#receivedgoods-pay').focusout(alert( "Handler for .focus() called."));
          

});

      $( "#receivedgoods-pay" ).focusout(function() {
          //alert( "Handler for .focus() called." );
           

           var a = $('#li-first').first();
           console.log(a);
            $(this).val(a.text());
            $('#pay-id-receivedgoods').val(a.val());
         
          $('#pay-receivedgoods').fadeOut();
            });
      
        
    
 

     
    
});

$(document).ready(function(){ 
     $('#receivedgoods-cn').keyup(function(e){
            //var idcli = $('#client-rg').val(); 
           var query = $(this).val();
           var vendor = $('#vendor-cn').val();
            
           if(query != '')  
           {  
                $.ajax({  
                     url:"searchreceivedgoods.php",  
                     method:"POST",  
                     data:{query:query,vendor:vendor},  
                     success:function(data)  
                     {  
                          $('#cn-receivedgoods').fadeIn();  
                          $('#cn-receivedgoods').html(data);  
                     }  
                });  
           } 


           $("list-rg-ca-items li:first-child").css("background-color", "yellow");
           

           var keyCode = e.keyCode || e.which; 

          if (keyCode == 113) { 
          e.preventDefault(); 
            
            
            } 
           
            
      });
       
      $('#vendor-cn').change(function(){
      var blank = '';
      $('#receivedgoods-cn').val(blank);
      $('#cn-id-receivedgoods').val(blank);
      
    
    })


      $(document).on('click', 'li', function(){ 
           

          $('#receivedgoods-cn').val($(this).text());
          $('#cn-id-receivedgoods').val($(this).val());
         
          $('#cn-receivedgoods').fadeOut();
          
          //$('#receivedgoods-pay').focusout(alert( "Handler for .focus() called."));
});

      $( "#receivedgoods-cn" ).focusout(function() {
          //alert( "Handler for .focus() called." );
           

           var a = $('#li-first').first();
           console.log(a);
            $(this).val(a.text());
            $('#cn-id-receivedgoods').val(a.val());
         
          $('#cn-receivedgoods').fadeOut();
            });
});

$(document).ready(function(){ 
     $('#receivedgoods-dn').keyup(function(e){
            //var idcli = $('#client-rg').val(); 
           var query = $(this).val();
           var vendor = $('#vendor-dn').val();
            
           if(query != '')  
           {  
                $.ajax({  
                     url:"searchreceivedgoods.php",  
                     method:"POST",  
                     data:{query:query,vendor:vendor},  
                     success:function(data)  
                     {  
                          $('#dn-receivedgoods').fadeIn();  
                          $('#dn-receivedgoods').html(data);  
                     }  
                });  
           } 


           $("list-rg-ca-items li:first-child").css("background-color", "yellow");
           

           var keyCode = e.keyCode || e.which; 

          if (keyCode == 113) { 
          e.preventDefault(); 
            
            
            } 
           
            
      });
       
      $('#vendor-dn').change(function(){
      var blank = '';
      $('#receivedgoods-dn').val(blank);
      $('#dn-id-receivedgoods').val(blank);
      
    
    })


      $(document).on('click', 'li', function(){ 
           

          $('#receivedgoods-dn').val($(this).text());
          $('#dn-id-receivedgoods').val($(this).val());
         
          $('#dn-receivedgoods').fadeOut();
          
          //$('#receivedgoods-pay').focusout(alert( "Handler for .focus() called."));
});

      $( "#receivedgoods-dn" ).focusout(function() {
          //alert( "Handler for .focus() called." );
           

           var a = $('#li-first').first();
           console.log(a);
            $(this).val(a.text());
            $('#dn-id-receivedgoods').val(a.val());
         
          $('#dn-receivedgoods').fadeOut();
            });
});

$('#add_reportca').click(function(){
     

     var movdate = $('#reportmovdate-ca').val();
     var datemov = $('#reportca-start-date').val();
     var datemovto = $('#reportca-end-date').val();
     var vendor = $('#vendor-ca').val();
     var client = $('#client-ca').val();
     var vendor_or_client = $('#reportcato-ca').val();
     var all = $('#report-check-all').prop('checked');
     var payments = $('#report-check-payment').prop('checked');
     var debitnotes = $('#report-check-debitnote').prop('checked');
     var creditnotes = $('#report-check-creditnote').prop('checked');
     var receivedgoods = $('#report-check-rg').prop('checked');
     var sales = $('#report-check-sales').prop('checked');
     var checkarray = [];
    

     if(payments){
      checkarray.push('currentaccount_type_id = 1');
     }
     if(debitnotes){
      checkarray.push('currentaccount_type_id = 2');
     }
     if(creditnotes){
      checkarray.push("currentaccount_type_id = 3");
     }
     if(receivedgoods){
      checkarray.push("currentaccount_type_id = 4");
     }
     if(sales){
      checkarray.push( "currentaccount_type_id = 5");
     }


  
     var resultado;
     var colums = '0,1,2,3,4,5,6,7,8';
     

    $.ajax({
    url:'reportcurrentaccount_infoaction.php',
    method:"POST",
    data:{vendor:vendor,client:client,action:'getVendorca'},
    dataType:"json",
    success:function(data1){


      
      if ($('#client-ca').val() === '0'){
         var column = '0,1,2,3,4';
         }
         else{
           var column = '0,1,2,3,4,5,6,7,8';
         }

      if ($('#vendor-ca').val() === '0'){
        
         var column = '0,1,2,3,4,5,6';
        }
        else{
           var column = '0,1,2,3,4,5,6,7,8';
        }
       
      $('#caModal').modal('show');
      $('#ca-modal-vendor').val(data1.name);
      var detailca = $('#detail-ca').DataTable({
       "lengthMenu": [[200, 75, 50, -1], [100, 75, 50, "all"]],
      "pageLength": 200,
 
       dom: 'Bfrtip',
   buttons:[
            'pageLength',
            {
                
                extend: 'copyHtml5',
                exportOptions: {
                  
                    columns: [column]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [column]
                }
            },
             {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [column]
                }
            },
        
        ],
      "lengthChange": false,
      "processing":true,
      "paging": false,
      "serverSide":true,
      "order":[],
      "ordering":false, 
      "searching":false,
      //"responsive": false,
      "ajax":{
      url:"reportcurrentaccount_infoaction.php",
      type:"POST",
      data:{action:'listItemca',vendor:vendor,client:client,vendor_or_client:vendor_or_client,payments:payments,debitnotes:debitnotes,creditnotes:creditnotes,receivedgoods:receivedgoods,
      all:all,checkarray:checkarray,movdate:movdate,datemov:datemov,datemovto:datemovto},
      dataType:"json"
       
      },
 
      
  
      
      });

        if ($('#client-ca').val() === '0'){
        detailca.column(5).visible(false);
        detailca.column(6).visible(false);
        detailca.column(7).visible(false);
        detailca.column(8).visible(false);
         
        }
        else{
           detailca.column(5).visible(true);
          detailca.column(6).visible(true);
          detailca.column(7).visible(true);
        }

        if ($('#vendor-ca').val() === '0'){
        detailca.column(7).visible(false);
        detailca.column(8).visible(false);
       
        }
        

        


      $('.modal-title').html("<i class='fa fa-plus'></i>Current Account Report");
    

    }
  })
  
  });

$('#close-rp-ca').click(function(){

$('#detail-ca').DataTable().destroy();
blank = '';

$('#vendor-ca').val(blank);
     
$('#client-ca').val(blank);

})

$('#closeCaModal').click(function(){

$('#detail-ca').DataTable().destroy();
blank = '';

$('#vendor-ca').val(blank);
     
$('#client-ca').val(blank);

})

//Sales edit

$(document).ready(function(){ 
var salesid = $('#salesid').val();
var detailsales = $('#detail-sales').DataTable({
   dom: 'Bfrtip',
   buttons:[
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14]
                }
            },
            {
                extend: 'excelHtml5', footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14]
                }
            },
            
            'colvis'
            ,
        'print'
        ],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "responsive": true,
  "ajax":{
    url:"actionsales.php",
    type:"POST",
    data:{action:'listItemsales', salesid:salesid},
    dataType:"json"
  },
 
  "pageLength": 20,
  "footerCallback": function (tfoot, data, start, end, display) {
            var api = this.api();
            var p = api.column(14).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(14).footer()).html("Total: " + p.toFixed(2));
            var api = this.api();
            var p = api.column(13).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(13).footer()).html("Total: " + p.toFixed(2));

            var api = this.api();
            var p = api.column(12).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(12).footer()).html("Total: " + p.toFixed(2));

            var api = this.api();
            var p = api.column(11).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(11).footer()).html("Total: " + p.toFixed(4));


          },
});

$('#detail-sales tbody').on( 'click', 'tr',function(){
        $(this).toggleClass('selected');
    });

$('#additem-sales').click(function(){
          
      var qty = $('#sales-code-qty').val();
      var uxb = $('#sales-code-uxb').val();
      $('#salesModal').modal('show');
      $('#salesForm')[0].reset();
      $('.modal-title').html("<i class='fa fa-plus'></i>Add item");
      $('#action').val('addItemsales');
      $('#save').val('Add');  
    });


     
 



/*

$('#additem-sales').click(function(){
      var saleid = $('#salesid').val();
       $('body').append('<div id="salesModal" class="modal" tabindex="-1" role="dialog">');
       $('#containerModal').remove();
      $.ajax({
      type:'POST',
      url:'modaladditemsales.php',
     // data: "dato=" + $('#product-categorie').val(),
     data:{saleid:saleid},
      dataType:'json',
      encode:true,
      
      success:function(r){
      $('#salesModal').html(r);
      var qty = $('#sales-code-qty').val();
      var uxb = $('#sales-code-uxb').val();
      $('#salesModal').modal('show');
      $('#salesForm')[0].reset();

      $('.modal-title').html("<i class='fa fa-plus'></i>Add item");
      $('#action').val('addItemsales');
      //$('#action-po').val('processpo');
      $('#save').val('Add');
        }
    });


     
  });
    
*/
$('#hide-totals-sales').click(function(){
    detailsales.column(10).visible(false);
    detailsales.column(11).visible(false);
    detailsales.column(12).visible(false);


   });

$('#show-totals-sales').click(function(){
    detailsales.column(10).visible(true);
    detailsales.column(11).visible(true);
    detailsales.column(12).visible(true);

   });
$("#detail-sales").on('click', '.update', function(){
  
  var item2 = $(this).attr("id");
  var s = $('#sales-id').val();
  var action = 'getItemsales';
  $.ajax({
    url:'actionsales.php',
    method:"POST",
    data:{item2:item2,action:action},
    dataType:"json",
    success:function(data){
      $('#salesModal').modal('show');
      $('#sales-id').val(data.id);
      $('#sales-code-name').val(data.clientcode_sales);
      $('#sales-productid').val(data.products_id);
      $('#sales-code-description').val(data.desc_pr_sales);
      $('#sales-code-uxb').val(data.uxb_sales);
      $('#sales-code-qty').val(data.qty_sales);
      $('#sales-code-fob').val(data.price_sales);
      $('#sales-code-ctn').val(data.ctn_sales);
      $('#sales-code-cbm').val(data.cbm_sales);
      $('#sales-code-gw').val(data.gw_sales);
      $('#sales-code-nw').val(data.nw_sales);
      $('#sales-qty-hidden').val(data.qty_sales);
      $('#sales-date').val(data.date);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit item");
      $('#action').val('updateItemsales');
      $('#save').val('Save');
    
      
      
    }
  })
 });



$("#salesModal").on('submit','#salesForm', function(event){
  event.preventDefault();
  $('#save').attr('disabled','disabled');
  var formData = $(this).serialize();

  $.ajax({
    url:"actionsales.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#salesForm')[0].reset();
      $('#salesModal').modal('hide');        
      $('#save').attr('disabled', false);
      detailsales.ajax.reload();
    }
    })


  });
 $("#detail-sales").on('click', '.delete', function(){
  var item = $(this).attr("id");   
  var action = "ItemsalesDelete";
  if(confirm("Are you sure you want to delete this code?")) {
    $.ajax({
      url:"actionsales.php",
      method:"POST",
      data:{item:item, action:action},
      success:function(data){          
       detailsales.ajax.reload();
      }
    })
  } 
  else {
    return false;
  }
});

});


$(document).ready(function(){ 
  chargeTps_sales();
      $('#sales-code-name').keyup(function(){
            var idcli = $('#client-sales').val(); 
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"searchcodeclientsales.php",  
                     method:"POST",  
                     data:{query:query,idcli:idcli},  
                     success:function(data)  
                     {  
                          $('#sales-clientcodename').fadeIn();  
                          $('#sales-clientcodename').html(data);  
                     }  
                });  
           }  
      });  
       
      


$( "#sales-code-name").on('focusin', function(event) {
  $('#sales-code-name').val('');
  $('#sales-productid').val('');
  $('#sales-code-description').val('');
  $('#sales-code-fob').val('');
  $('#sales-code-qty').val('');
  $('#sales-code-uxb').val('');
  $('#sales-code-ctn').val('');
  $('#sales-code-cbm').val('');
  $('#sales-code-gw').val('');
  $('#sales-code-nw').val('');

});
 $("#sales-code-name").keydown(function(e) {
        var keyCode = e.keyCode || e.which; 

          if (keyCode === 9) {
           var product = $('#sales-productid').val();
            
           



           var firstli = $('#li-first').first();
           var clientcode = firstli.attr('clientcode');
            $(this).val(clientcode);
            var uxbValue = firstli.attr('uxb');
            $('#sales-code-uxb').val(uxbValue);
            $('#sales-productid').val(firstli.val());
            $('#sales-clientcodename').fadeOut();
              
              
                      
            
              chargeTps_sales();
              
              
                    
             

              
          
         
            
            

          }
          
              

            });
     
      $(document).on('click','li', function(){  
         
          
          
           $('#sales-code-name').val($(this).text());
           $('#sales-productid').val($(this).val());
           var uxbValue = $(this).attr('uxb');
           var clientcode = $(this).attr('clientcode');
           $('#sales-code-name').val(clientcode);
           $('#sales-code-uxb').val(uxbValue);
           $('#sales-clientcodename').fadeOut();
            
              
             

              
            
              chargeTps_sales();
              
              $('#sales-clientcodename').fadeOut();
        


});


     

 
});
$("#sales-code-qty" ).focusout(function() {
          //alert( "Handler for .focus() called." );
           
              var validate_items_qty = validate_sales_items_qty();
              validate_items_qty.success(function (data){
              var item_val = data;
             
              if (item_val === true){
              $('#sales-code-qty').val('');
             alert('QTY EXCEED THE STOCK AVAILABLE');
            
           
             
             
              }
             
              
             

              
          })

         
            });

$("#sales-code-qty").keydown(function(e) {
        var keyCode = e.keyCode || e.which; 

          if (keyCode === 9) {
           
    
              var validate_items_qty = validate_sales_items_qty();
              validate_items_qty.success(function (data){
              var item_val = data;
             
              if (item_val === true){
                $('#sales-code-qty').val('');
             alert('QTY EXCEED THE STOCK AVAILABLE');
            
           
             
             
              }

              
             

              
          })
         
            
            

          }
          
              

            });
            

function validate_sales_items(){

 var prid = $('#sales-productid').val();   
 var salesid = $('#salesid').val();
 var sales_tp = $('#sales-tp').val();
 var uxb = $('#sales-code-uxb').val();

     return $.ajax({
            url:'validate_sales_items.php',
            method:"POST",
            data:{prid:prid,salesid:salesid,sales_tp:sales_tp,uxb:uxb},
            dataType:"json",
            success: function(data){
              console.log(data);
            }
            
        })


}
function validate_sales_items_qty(){

 var prid = $('#sales-productid').val();   
 var salestp = $('#sales-tp').val();
 var sales_qty = $('#sales-code-qty').val();

     return $.ajax({
            url:'validate_sales_items_qty.php',
            method:"POST",
            data:{prid:prid,salestp:salestp,sales_qty:sales_qty},
            dataType:"json",
            success: function(data){
              console.log(data);
            }
            
        })


}
      
function chargeTps_sales(tp){   
 //var tps = $('#rg-tp').val();
    var tp_ids = tp;
    var clientid = $('#clientid_sales').val();
    var idproduct = $('#sales-productid').val();
    var clientcode = $('#sales-code-name').val();
    var uxb = $('#sales-code-uxb').val();
    $.ajax({
      type:'POST',
      url:'aj_tp_sales.php', 
     // data: "dato=" + $('#product-categorie').val(),
     data:{idproduct:idproduct,tp_ids:tp_ids,clientcode:clientcode,clientid:clientid,uxb:uxb},
      dataType:'json',
      encode:true,
      
      success:function(r){
        $('#sales-tps').html(r);
        
        }
    });
  }
    
 
$('#sales-tps').change(function(){
      
      var idproduct = $('#sales-productid').val();

           var salesid = $('#salesid').val();
           var sales_tp = $('#sales-tp').val();
           var validate_items = validate_sales_items();
              validate_items.success(function (data){
              var item_val = data;
             
              if (item_val === true){

             alert('This product is duplicated');
            
           
             $('#sales-code-name').val('');
             $('#sales-productid').val('');
             $('#sales-tp').val('');
              }         
                

               else{

                     $.ajax({
                url:'ajax_sales_items.php',
                method:"POST",
                data:{idproduct:idproduct,sales_tp:sales_tp},
                dataType:"json",
                success:function(data){ 
                  $('#sales-code-description').val(data.desc_pr_rg);
                  $('#sales-code-fob').val(data.price_rg);
                  if (data.invoiced ==='0'){
                  $('#sales-code-qty').attr('placeholder', data.qty_rg+' IS A MAX QTY');
                  }
                  if (data.invoiced ==='3'){
                  $('#sales-code-qty').attr('placeholder', data.partial_invoice+' IS A MAX QTY');
                  }

                  $('#sales-code-ctn').val(data.ctn);
                  $('#sales-code-cbm').val(data.cbm_rg);
                  $('#sales-code-gw').val(data.gw);
                  $('#sales-code-nw').val(data.nw);

              
                    }
              })  
             
                  }
                })

           
      
    
    })
     
    


$(document).ready(function(){
 
 $("input[type='number']").change(function(){
var result = 0;
var result2 = '';
var result3 = '';
var uxb = $("input[id='sales-code-uxb']").val();
var qty = $("input[id='sales-code-qty']").val();
var result = qty / uxb;
    if(uxb && qty != ''){
      if(Number.isInteger(result))
           {
        $("#sales-code-ctn").val(result);
          }
      else{
        $("#sales-code-ctn").val(result2);
        //$("#sales-code-uxb").val(result3);
        $("#sales-code-qty").val(result3);
        alert("CTN is not integer number");
        }
    }

    
  });
});

//Customer alias inserts
$(document).ready(function(){ 
var prid = $('#product-id').val();
var clientcode = $('#clientcode').DataTable({
 "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "responsive": true,
  "ajax":{
    url:"actioncodeclient.php",
    type:"POST",
    data:{action:'listClientcode',prid:prid},
    dataType:"json"
  },
 "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 5
});


$('#addclientcode').click(function(){
    $('#clientcodeModal').modal('show');
    $('#clientcodeForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add Customer alias");
    $('#action').val('addClientcode');
    $('#save').val('Submit');
    $("#tabProduct").tabs("enable");
  
  });   

$('#closecli').click(function(){
             location.reload();
        });

$('#closecli1').click(function(){
             location.reload();
});

$('#PrInfo').click(function(){

    var validate_items_alias = validate_customerAlias();
              validate_items_alias.success(function (data){
              var item_val = data;
             
              if (item_val === false){
                alert('Please insert Customer Alias');
                location.reload();
              
            }
           
})
    
     
    
});


function validate_customerAlias(){ 

 var prid = $('#clientcodeProductid').val();

     return $.ajax({
            url:'validate_customeralias.php',
            method:"POST",
            data:{prid:prid},
            dataType:"json",
            success: function(data){
              console.log(data);
            }
            
        })


}

$("#clientcode").on('click', '.update', function(){
  var clientcodeId = $(this).attr("id");
  var action = 'getClientcode';
  $.ajax({
    url:'actioncodeclient.php',
    method:"POST",
    data:{clientcodeId:clientcodeId, action:action},
    dataType:"json",
    success:function(data){
      $('#clientcodeModal').modal('show');
      $('#clientcodeId').val(data.id);
      $('#clientcodeName').val(data.clientcode);
      $('#clientcodeClient').val(data.clients_id);
      $('#clientcodeProductid').val(data.products_id); 
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit Customer alias");
      $('#action').val('updateClientcode');
      $('#save').val('Save');
    }
  })
 }); 

/*
  $('#save').attr('disabled','disabled');
   var validate_items = validate_customer_items();
              validate_items.success(function (data){
              var item_val = data;
             
              if (item_val === true){

             alert('This Customer alias is duplicated');
              $('#clientcodeForm')[0].reset();
               $('#clientcodeModal').modal('hide');        
              $('#save').attr('disabled', false);
              clientcode.ajax.reload();
            }
            else
            {
  var formData = $(this).serialize();
  $.ajax({
    url:"actioncodeclient.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#clientcodeForm')[0].reset();
      $('#clientcodeModal').modal('hide');        
      $('#save').attr('disabled', false);
      clientcode.ajax.reload();
    }
    })
}
})
  });   

*/
$("#clientcodeModal").on('submit','#clientcodeForm', function(event){
  event.preventDefault();
  var button = $('#save').val();
  $('#save').attr('disabled','disabled');
  
  if (button === 'Submit'){
      var formData = $(this).serialize();
  
      var validate_items = validate_customer_items();
              validate_items.success(function (data){
              var item_val = data;
             
              if (item_val === true){

             alert('This Customer alias is duplicated or this customer already has an alias assigned');
              $('#clientcodeForm')[0].reset();
               $('#clientcodeModal').modal('hide');        
              $('#save').attr('disabled', false);
              clientcode.ajax.reload();
            }
            else
            {



  $.ajax({
    url:"actioncodeclient.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#clientcodeForm')[0].reset();
      $('#clientcodeModal').modal('hide');        
      $('#save').attr('disabled', false);
      clientcode.ajax.reload();
    }
    })
}
})
}
else{

    var formData = $(this).serialize();

    $.ajax({
    url:"actioncodeclient.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#clientcodeForm')[0].reset();
      $('#clientcodeModal').modal('hide');        
      $('#save').attr('disabled', false);
      clientcode.ajax.reload();
    }
    })

}



  });   

$("#clientcode").on('click', '.delete', function(){
  var client = $(this).attr("id");   
  var action = "clientcodeDelete";
  if(confirm("Are you sure you want to delete this Customer alias?")) {
    $.ajax({
      url:"actioncodeclient.php",
      method:"POST",
      data:{client:client, action:action},
      success:function(data){          
       clientcode.ajax.reload();
      }
    })
  } 
  else {
    return false;
  }
});

 });

function validate_customer_items(){ 

 var cliId = $('#clientcodeClient').val();   
 var clientcode = $('#clientcodeName').val();
 var prid = $('#clientcodeProductid').val();

     return $.ajax({
            url:'validate_customer_items.php',
            method:"POST",
            data:{cliId:cliId,clientcode:clientcode,prid:prid},
            dataType:"json",
            success: function(data){
              console.log(data);
            }
            
        })


}

$(document).ready(function(){ 
var saleid = $('#saleid').val();
var container = $('#sales-container').DataTable({
 "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  "searching":false,
  "responsive": true,
  "ajax":{
    url:"actioncontainer.php",
    type:"POST",
    data:{action:'listContainer',saleid:saleid},
    dataType:"json"
  },
 "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 5
});


$('#addcontainer').click(function(){
 
   $('#containerModal').modal('show');
    $('#containerForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add container");
    $('#action1').val('addContainer');
    $('#save1').val('Add');

   
  });   




$("#sales-container").on('click', '.update', function(){
  var containerId = $(this).attr("id");
  var action = 'getContainer';
  $.ajax({
    url:'actioncontainer.php',
    method:"POST",
    data:{containerId:containerId, action:action},
    dataType:"json",
    success:function(data){
      $('#containerModal').modal('show');
      $('#ContainerName').val(data.name);
      $('#ContainerSealn').val(data.sealn);
      $('#container-id').val(data.id);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit Container");
      $('#action1').val('updateContainer');
      $('#save1').val('Save');
    }
   })
 }); 

$("#containerModal").on('submit','#containerForm', function(event){
  event.preventDefault();
  $('#save1').attr('disabled','disabled');
  var formData = $(this).serialize();

  $.ajax({
    url:"actioncontainer.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#containerForm')[0].reset();
      $('#containerModal').modal('hide');        
      $('#save1').attr('disabled', false);
      container.ajax.reload();
    }
    })
  });

     

$("#sales-container").on('click', '.delete', function(){
  var con = $(this).attr("id");   
  var action = "containerDelete";
  if(confirm("Are you sure you want to delete this Container?")) {
    $.ajax({
      url:"actioncontainer.php",
      method:"POST",
      data:{con:con,action:action},
      success:function(data){          
       container.ajax.reload();
      }
    })
  } 
  else {
    return false;
  }
});

 });


$(document).ready(function(){
//var prid = $('#product-id').val();
var salesinfo = $('#sales-info').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"sales_infoaction.php",
    type:"POST",
    data:{action:'listSales'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 10
});

$("#sales-info").on('click', '.download', function(){
  var id = $(this).attr("id");
  var inv = $(this).attr("download");
  var action = 'downloadSales';
  
    $.ajax({
      url:"sales_infoaction.php",
      method:"POST",
      data:{id:id,action:action},
      success:function(data){
       
       window.location="invoices/"+inv;
       salesinfo.ajax.reload();
      }
    })
   
  
});
});

$(document).ready(function(){ 
var saleid = $('#saleid').val();
var shippingcost = $('#sales-shippingcost').DataTable({
 "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  "searching":false,
  "responsive": true,
  "ajax":{
    url:"actionshippingcost.php",
    type:"POST",
    data:{action:'listShippingcost',saleid:saleid},
    dataType:"json"
  },
 "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 5
});



$('#addshippingcost').click(function(){
 
   $('#shippingcostModal').modal('show');
    $('#shippingcostForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add Shipping Cost");
    $('#action2').val('addShippingcost');
    $('#save2').val('Add');

   
  });   

$("#shippingcostModal").on('submit','#shippingcostForm', function(event){
  event.preventDefault();
  $('#save2').attr('disabled','disabled');
  var formData = $(this).serialize();

  $.ajax({
    url:"actionshippingcost.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#shippingcostForm')[0].reset();
      $('#shippingcostModal').modal('hide');        
      $('#save2').attr('disabled', false);
      shippingcost.ajax.reload();
    }
    })
  });



$("#sales-shippingcost").on('click', '.update', function(){
  var shippingcostId = $(this).attr("id");
  var action = 'getShippingcost';
  $.ajax({
    url:'actionshippingcost.php',
    method:"POST",
    data:{shippingcostId:shippingcostId, action:action},
    dataType:"json",
    success:function(data){
      $('#shippingUpdateModal').modal('show');
      $('#concept').val(data.concept);
      $('#currencyrate').val(data.currencyrate);
      $('#usd').val(data.price_usd);
      $('#rmb').val(data.price_rmb);
      $('#updatesc-id').val(data.id);

      $('.modal-title').html("<i class='fa fa-plus'></i> Edit");
      $('#action5').val('updateShippingcost');
      $('#save5').val('Save');
    }
   })
 }); 

$("#shippingUpdateModal").on('submit','#shippingUpdateForm', function(event){
  event.preventDefault();
  $('#save5').attr('disabled','disabled');
  var formData = $(this).serialize();

  $.ajax({
    url:"actionshippingcost.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#shippingUpdateForm')[0].reset();
      $('#shippingUpdateModal').modal('hide');        
      $('#save5').attr('disabled', false);
      shippingcost.ajax.reload();
    }
    })
  });



     

$("#sales-shippingcost").on('click', '.delete', function(){
  var con = $(this).attr("id");   
  var action = "shippingcostDelete";
  if(confirm("Are you sure you want to delete this Concept?")) {
    $.ajax({
      url:"actionshippingcost.php",
      method:"POST",
      data:{con:con,action:action},
      success:function(data){          
       shippingcost.ajax.reload();
      }
    })
  } 
  else {
    return false;
  }
});

 });



$(document).ready(function(){
 
 $("input[id='senyefeeRMB']").change(function(){

var sfcr = $("input[id='senyefeeCR']").val();
var sfrmb = $("input[id='senyefeeRMB']").val();
var sfresult = sfrmb / sfcr;

if(sfresult != '' || '0'){
$("#senyefeeUSD").val(sfresult.toFixed(2));
    }

$("input[id='delfinfeeRMB']").change(function(){
var dfcr = $("input[id='delfinfeeCR']").val();
var dfrmb = $("input[id='delfinfeeRMB']").val();
var dfresult = dfrmb / dfcr;

if(dfresult != '' || '0'){
$("#delfinfeeUSD").val(dfresult.toFixed(2));
    }

});

$("input[id='warehousefeeRMB']").change(function(){
var wfcr = $("input[id='warehousefeeCR']").val();
var wfrmb = $("input[id='warehousefeeRMB']").val();
var wfresult = wfrmb / wfcr;

if(wfresult != '' || '0'){
$("#warehousefeeUSD").val(wfresult.toFixed(2));
    }

  });

$("input[id='loadingfeeRMB']").change(function(){
    var lfcr = $("input[id='loadingfeeCR']").val();
var lfrmb = $("input[id='loadingfeeRMB']").val();
var lfresult = lfrmb / lfcr;

if(lfresult != '' || '0'){
$("#loadingfeeUSD").val(lfresult.toFixed(2));
    }
 });

$("input[id='invlegfeeRMB']").change(function(){
    var ilcr = $("input[id='invlegfeeCR']").val();
var ilrmb = $("input[id='invlegfeeRMB']").val();
var ilresult = ilrmb / ilcr;

if(ilresult != '' || '0'){
$("#invlegfeeUSD").val(ilresult.toFixed(2));
    }

});

$("input[id='pricelistfeeRMB']").change(function(){
    var plcr = $("input[id='pricelistfeeCR']").val();
var plrmb = $("input[id='pricelistfeeRMB']").val();
var plresult = plrmb / plcr;

if(plresult != '' || '0'){
$("#pricelistfeeUSD").val(plresult.toFixed(2));
    }
});

$("input[id='expodecfeeRMB']").change(function(){
    var edcr = $("input[id='expodecfeeCR']").val();
var edrmb = $("input[id='expodecfeeRMB']").val();
var edresult = edrmb / edcr;

if(edresult != '' || '0'){
$("#expodecfeeUSD").val(edresult.toFixed(2));
    }
});

$("input[id='colegfeeRMB']").change(function(){
    var clcr = $("input[id='colegfeeCR']").val();
var clrmb = $("input[id='colegfeeRMB']").val();
var clresult = clrmb / clcr;

if(clresult != '' || '0'){
$("#colegfeeUSD").val(clresult.toFixed(2));
    }

});


  });
});

$(document).ready(function(){
$("input[id='rmb']").change(function(){

var ucr = $("input[id='currencyrate']").val();
var urmb = $("input[id='rmb']").val();
var uresult = urmb / ucr;

if(uresult != '' || '0'){
$("#usd").val(uresult.toFixed(2));
    }
  });  
  });



/*
$(document).ready(function(){
//var prid = $('#product-id').val();
var customerstock = $('#customerStock-info').DataTable({
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"customerStock_infoaction.php",
    type:"POST",
    data:{action:'listCustomerStock'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[2,3],
      "orderable":false,
    },
  ],
  "pageLength": 20
});


});
*/
$('#stockCustomerSelect').change(function(){
$('#customerStock-info').DataTable().destroy();
var filter = $('#stockCustomerSelect').val();
var customerstock = $('#customerStock-info').DataTable({
  "lengthMenu": [[200, 75, 50, -1], [100, 75, 50, "all"]],
   "pageLength": 200,
  dom: 'Bfrtip',
  buttons: ['pageLength','copy', 'csv', 'excel', 'pdf', 'print'],
   "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"customerStock_infoaction.php",
    type:"POST",
    data:{action:'listCustomerStock',filter:filter},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[2,3],
      "orderable":false,
    },
  ],
  

});
});

$(document).ready(function()
        {
            
             $("#tabStock").tabs();
            
            
           
           
        });
$(document).ready(function()
        {
          $("#tabProduct").tabs();
          $("#tabProduct").tabs("disable");



            });

$('#addproduct12').click(function(){
    $.ajax({
    url:'add_product1.php',
    method:"POST",
    dataType:"json",
    success:function(data){
    window.location.replace("edit_product.php?id="+data.id)
    console.log(data.id);

    }
   })


});

$(document).ready(function()
        {
            
      $("#tabProductBis").tabs();


$("#productss-info").on('click', '.editproduct', function(){

var item = $(this).attr("id");
$(this).attr('href', 'edit_product_bis.php?id='+item);


 });          
 });

$(document).ready(function()
        {
            
             $("#tabProductRd").tabs();
            
            
           
           
        });


$(document).ready(function()
        {
            
             $("#tabSales").tabs();
            
            
           
           
        });




$(document).ready(function()
        {
            
            
 $('#desaprobe').click(function(){
  
  var action = 'desaprobar';
  
    $.ajax({
      url:"prueba_infoaction.php",
      method:"POST",
      data:{action:action},
      success:function(data){
       confirm("Are you sure you want to delete this Concept?");
      
      }
    })
   
  
}); 
            
           
           
        });

$('#client-sales').change(function(){

    client = $(this).val();

    $.ajax({
      url:"ajax_comission_sales.php",
      method:"POST",
      data:{client:client},
      dataType:"json",
      success:function(data){

       $('#comission-sales').val(data.comission);
      
      }
    })

});


$('#exportaccounts').click(function(){

 var action ='exportAccounts';
 var idcustomer = $('#exp_accounts_customer').val();


$.ajax({
      url:"export_infoaction.php",
      method:"POST",
      data:{idcustomer:idcustomer,action:action},
      success:function(data){
       
      window.location="invoices/currentaccounts.xls";
       
      }
    })


});

$('#export_products1').click(function(){

 var action ='exportProducts';
 var idcustomer1 = $('#exp_customer_products1').val();

 $.ajax({
      url:"delete_export_products.php",
      method:"POST",
      success:function(data){

$.ajax({
      url:"export_infoaction.php",
      method:"POST",
      data:{idcustomer1:idcustomer1,action:action},
      success:function(data){
       
      window.location="exports/exportproducts.xls";
       
      }
    })

     

      }


       })


});

$('#export_makers').click(function(){

 var action ='exportMakers';
 var idmaker = $('#exp_makers').val();


  $.ajax({
      url:"delete_export_makers.php",
      method:"POST",
      success:function(data){


$.ajax({
      url:"export_infoaction.php",
      method:"POST",
      data:{idmaker:idmaker,action:action},
      success:function(data){
       
      window.location="exports/exportmakers.xls";
      
      }
    })

     

      }


       })


});

/*
$(document).ready(function(){
    $('#file_upload').change(function(e) {

      $('#img_products')[0].src = (window.URL ? URL : webkitURL).createObjectURL(input.files[0]);
           // $('img_products').attr('src', e.target.result);
        
        
        var fileName = e.target.result;
        
        $('#img_products').attr('src',fileName);
        
       //alert('The file name is : "' + fileName);
      });
});
*/
$(document).ready(()=>{
      $('#file_upload').change(function(){
        const file = this.files[0];
        console.log(file);
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            console.log(event.target.result);
            $('#img_products').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });
    });

$('#add_company').click(function(){
var concept = $('#concept-company').val();
var price = $('#price-company').val();
var observation = $('#observation-company').val();
var date = new Date();

var strDate = date.getFullYear() + "/" + (date.getMonth()+1) + "/" + date.getDate();
  $.ajax({
    url:'companymovements_info.php',
    method:"POST",
    data:{concept:concept,price:price,observation:observation,strDate:strDate},
    dataType:"json",
    success:function(data){
      
      $('#concept-company').val('');
      $('#price-company').val('');
      $('#observation-company').val('');
      window.location="companymovements.php";
    }
  })
 }); 

$('#add_reportcompany').click(function(){
     
     var datestart = $('#reportcompany-start-date').val();
     var dateend = $('#reportcompany-end-date').val();

      
      $('#companyModal').modal('show');

      var detailcomp = $('#detail-company').DataTable({
      "pageLength" : 200,
      dom: 'Bfrtip',
       
      "lengthMenu": [[200, 100, 50, -1], [200, 100, 50, "All"]],
      buttons: [
        'pageLength',
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
            {
                extend: 'excelHtml5', footer: true,
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            },
              {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0,1,2,3,4],footer: true,
                  }
                },
            
        'print'
        ],
      "lengthChange": false,
      "processing":true,
      "paging": false,
      "serverSide":true,
      "order":[],
      "ordering":false, 
      "searching":true,
      //"responsive": false,
      "ajax":{
      url:"report_company_infoaction.php",
      type:"POST",
      data:{action:'listCompany',datestart:datestart,dateend:dateend},
      dataType:"json"
      },
     
        "footerCallback": function (tfoot, data, start, end, display) {
            var api = this.api();
            var p = api.column(4).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(4).footer()).html("Total: " + p.toFixed(2));


          },
      
      

      
    
});
 $('.modal-title').html("<i class='fa fa-plus'></i>Company movements report");
  
  });

 
$('#close-company').click(function(){

$('#detail-company').DataTable().destroy();


})

$('#closeCompanyModal').click(function(){

$('#detail-company').DataTable().destroy();


})

$(document).ready(function(){
//var prid = $('#product-id').val();
var productsRinfo = $('#receive-info').DataTable({
   
   "lengthMenu": [[200, 75, 50, -1], [100, 75, 50, "all"]],
   "pageLength": 200,
  dom: 'Bfrtip',
  buttons: [
     'pageLength',
            
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0,1,5,3,4,2,6,7,8,9,10,11,12,13,14,15]
                }
            },
             {
                        

                        text: 'Excel with Images',
                            
                            columns: [0,1,5,3,4,2,6,7,8,9,10,11,12,13,14,15],
                             format: {
                                    body: function (data, row, column, node) {
                                    if (column === 6) { // Check if it's the column for image
                                    // Extract the image URL from the HTML
                                    var name = $(data).val();
                                    return name; // Return hyperlink to the image URL
                                  }
                                  return data; // Return the original data for other columns
                                }
                                        
                                        
                                        },
                              
                                
                    
                   

                             action: function (e, dt, node, config) {
                                

                               $.ajax({
                                url: 'save_excel_r.php',
                                method: 'POST',
                                data: { data: JSON.stringify(dt.data().toArray()) },
                                success: function(response) {
                                    // Handle the response from the server
                                    if (response === 'success') {
                                       var downloadLink = document.createElement('a');
                                         downloadLink.href = 'exports/Outstanding.xls';
                                         downloadLink.download = 'Outstanding.xls';
                                         downloadLink.click();
                                            
                                    } else {
                                        // An error occurred while saving the file
                                        alert('Error saving Excel file!');
                                    }
                                },
                                error: function() {
                                    // An error occurred during the AJAX request
                                    alert('Error occurred during AJAX request!');
                                }
                            });

                        
                           
                        }


                            

                      
                    },
            {
                extend: 'excelHtml5',
                
                exportOptions: {
                 
                  columns: [0,4,5,3,1,2,6,7,8,9,10,11,12,13,14]
                   
                }
            },
             {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0,4,5,3,1,2,6,7,8,9,10,11,12,13,14]
                  }
                },
            
        'print'
        ],
  "lengthChange": true,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"products_rd_infoaction.php",
    type:"POST",
    data:{action:'listProductR'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  
});
});

$(document).ready(function(){
//var prid = $('#product-id').val();
var productsDinfo = $('#deliver-info').DataTable({
   "lengthMenu": [[200, 75, 50, -1], [100, 75, 50, "all"]],
   "pageLength": 200,
  dom: 'Bfrtip',
  buttons: [ 'pageLength',
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0,4,5,3,1,2,6,7,8,9,10,11,12,13,14]
                }
            },
                {
                        

                        text: 'Excel with Images',
                            
                           

                             action: function (e, dt, node, config) {
                                

                               $.ajax({
                                url: 'save_excel_d.php',
                                method: 'POST',
                                data: { data: JSON.stringify(dt.data().toArray()) },
                                success: function(response) {
                                    // Handle the response from the 1server
                                    if (response === 'success') {
                                       var downloadLink = document.createElement('a');
                                         downloadLink.href = 'exports/stockcheckup.xls';
                                         downloadLink.download = 'stockcheckup.xls';
                                         downloadLink.click();
                                            
                                    } else {
                                        // An error occurred while saving the file
                                        alert('Error saving Excel file!');
                                    }
                                },
                                error: function() {
                                    // An error occurred during the AJAX request
                                    alert('Error occurred during AJAX request!');
                                }
                            });

                        
                           
                        }


                            

                      
                    },



            {
                extend: 'excelHtml5',
                
                exportOptions: {
                 
                  columns: [0,4,5,3,1,2,6,7,8,9,10,11,12,13,14]
                   
                }
            },

             {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0,4,5,3,1,2,6,7,8,9,10,11,12,13,14]
                  }
                },
            
        'print'
        ],
  "lengthChange": true,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"products_rd_infoaction.php",
    type:"POST",
    data:{action:'listProductD'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  
});
});






$(document).ready(function(){


$('#client-rg').change(function(){
  
  $('#vendor-rg').empty();

   client = $('#client-rg').val();

    $.ajax({
      url:"aj_rg_customer.php",
      method:"POST",
      data:{client:client},
      dataType:"json",
      success:function(data){
         $('#vendor-rg').empty();
    var dat = data;
 

    var selectElem = $("#vendor-rg");
    
    // Iterate over object and add options to select
    $.each(dat, function(index, value){
        $("<option/>", {
            value: value.vid,
            text: value.vid + ' - ' +value.vname
        }).appendTo(selectElem);
    });

       console.log(data);
       //$('#vendor-rg').text(data.vname);
       
      
      }
    })

});
});

$(document).ready(function()
        {
            
             $("#tabSettings").tabs();
            
            
           
           
        });

$(document).ready(function(){
//var prid = $('#product-id').val();
var pricetypeinfo = $('#pricetype-info').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"settings_infoaction.php",
    type:"POST",
    data:{action:'listPricetype'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 20
});


$('#addpricetype').click(function(){
     $('#pricetypeModal').modal('show');
    $('#pricetypeForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add Price type");
    $('#action').val('addPricetype');
    $('#save').val('Add');
  });   




$("#pricetype-info").on('click', '.update', function(){
  var pricetypeId = $(this).attr("id");
  var action = 'getPricetype';
  $.ajax({
    url:'settings_infoaction.php',
    method:"POST",
    data:{pricetypeId:pricetypeId, action:action},
    dataType:"json",
    success:function(data){
      $('#pricetypeModal').modal('show');
      $('#pricetypeId').val(data.id);
      $('#pricetypeName').val(data.price_type);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit Price type");
      $('#action').val('updatePricetype');
      $('#save').val('Save');
    }
  })
 }); 

$("#pricetypeModal").on('submit','#pricetypeForm', function(event){
  event.preventDefault();
  $('#save').attr('disabled','disabled');
  
  var formData = $(this).serialize();
  
  $.ajax({
    url:"settings_infoaction.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#pricetypeForm')[0].reset();
      $('#pricetypeModal').modal('hide');        
      $('#save').attr('disabled', false);
      pricetypeinfo.ajax.reload();
    }
    })

})
  

$("#pricetype-info").on('click', '.delete', function(){
  var pricetype = $(this).attr("id");   
  var action = "pricetypeDelete";
  if(confirm("Are you sure you want to delete this Price Type?")) {
    $.ajax({
      url:"settings_infoaction.php",
      method:"POST",
      data:{pricetype:pricetype,action:action},
      success:function(data){          
       pricetypeinfo.ajax.reload();
      }
    })
  } 
 
});

});

$(document).ready(function(){
//var prid = $('#product-id').val();
var currencyinfo = $('#currency-info').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"settings_infoaction.php",
    type:"POST",
    data:{action:'listCurrency'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 20
});

$('#addcurrency').click(function(){
    $('#currencyModal').modal('show');
    $('#currencyForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add Currency");
    $('#action1').val('addCurrency');
    $('#save1').val('Add');
  });   




$("#currency-info").on('click', '.update', function(){
  var currencyId = $(this).attr("id");
  var action = 'getCurrency';
  $.ajax({
    url:'settings_infoaction.php',
    method:"POST",
    data:{currencyId:currencyId, action:action},
    dataType:"json",
    success:function(data){
      $('#currencyModal').modal('show');
      $('#currencyId').val(data.id);
      $('#currencyName').val(data.moneytype);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit Currency");
      $('#action1').val('updateCurrency');
      $('#save1').val('Save');
    }
  })
 }); 

$("#currencyModal").on('submit','#currencyForm', function(event){
  event.preventDefault();
  $('#save1').attr('disabled','disabled');
  
  var formData = $(this).serialize();
  
  $.ajax({
    url:"settings_infoaction.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#currencyForm')[0].reset();
      $('#currencyModal').modal('hide');        
      $('#save1').attr('disabled', false);
      currencyinfo.ajax.reload();
    }
    })

})
  

$("#currency-info").on('click', '.delete', function(){
  var currency = $(this).attr("id");   
  var action = "currencyDelete";
  if(confirm("Are you sure you want to delete this Currency?")) {
    $.ajax({
      url:"settings_infoaction.php",
      method:"POST",
      data:{currency:currency,action:action},
      success:function(data){          
       currencyinfo.ajax.reload();
      }
    })
  } 
 
});

});

$(document).ready(function(){
//var prid = $('#product-id').val();
var unitsinfo = $('#units-info').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"settings_infoaction.php",
    type:"POST",
    data:{action2:'listUnits'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 20
});

$('#addunits').click(function(){
    $('#unitsModal').modal('show');
    $('#unitsForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add units");
    $('#action2').val('addUnits');
    $('#save2').val('Add');
  });   




$("#units-info").on('click', '.update', function(){
  var unitsId = $(this).attr("id");
  var action2 = 'getUnits';
  $.ajax({
    url:'settings_infoaction.php',
    method:"POST",
    data:{unitsId:unitsId, action2:action2},
    dataType:"json",
    success:function(data){
      $('#unitsModal').modal('show');
      $('#unitsId').val(data.id);
      $('#unitsName').val(data.unittype);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit units");
      $('#action2').val('updateUnits');
      $('#save2').val('Save');
    }
  })
 }); 

$("#unitsModal").on('submit','#unitsForm', function(event){
  event.preventDefault();
  $('#save2').attr('disabled','disabled');
  
  var formData = $(this).serialize();
  
  $.ajax({
    url:"settings_infoaction.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#unitsForm')[0].reset();
      $('#unitsModal').modal('hide');        
      $('#save2').attr('disabled', false);
      unitsinfo.ajax.reload();
    }
    })

})
  

$("#units-info").on('click', '.delete', function(){
  var units = $(this).attr("id");   
  var action2 = "unitsDelete";
  if(confirm("Are you sure you want to delete this units?")) {
    $.ajax({
      url:"settings_infoaction.php",
      method:"POST",
      data:{units:units,action2:action2},
      success:function(data){          
       unitsinfo.ajax.reload();
      }
    })
  } 
 
});

});


$(document).ready(function(){
//var prid = $('#product-id').val();
var packagingsinfo = $('#packagings-info').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"settings_infoaction.php",
    type:"POST",
    data:{action3:'listPackagings'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 20
});

$('#addpackagings').click(function(){
    $('#packagingsModal').modal('show');
    $('#packagingsForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add packagings");
    $('#action3').val('addPackagings');
    $('#save3').val('Add');
  });   




$("#packagings-info").on('click', '.update', function(){
  var packagingsId = $(this).attr("id");
  var action3 = 'getPackagings';
  $.ajax({
    url:'settings_infoaction.php',
    method:"POST",
    data:{packagingsId:packagingsId, action3:action3},
    dataType:"json",
    success:function(data){
      $('#packagingsModal').modal('show');
      $('#packagingsId').val(data.id);
      $('#packagingsName').val(data.packagingtype);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit packagings");
      $('#action3').val('updatePackagings');
      $('#save3').val('Save');
    }
  })
 }); 

$("#packagingsModal").on('submit','#packagingsForm', function(event){
  event.preventDefault();
  $('#save3').attr('disabled','disabled');
  
  var formData = $(this).serialize();
  
  $.ajax({
    url:"settings_infoaction.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#packagingsForm')[0].reset();
      $('#packagingsModal').modal('hide');        
      $('#save3').attr('disabled', false);
      packagingsinfo.ajax.reload();
    }
    })

})
  

$("#packagings-info").on('click', '.delete', function(){
  var packagings = $(this).attr("id");   
  var action3 = "packagingsDelete";
  if(confirm("Are you sure you want to delete this packagings?")) {
    $.ajax({
      url:"settings_infoaction.php",
      method:"POST",
      data:{packagings:packagings,action3:action3},
      success:function(data){          
       packagingsinfo.ajax.reload();
      }
    })
  } 
 
});



});

$(document).ready(function(){
//var prid = $('#product-id').val();
var companyinfo = $('#company-info').DataTable({
  dom: 'Bfrtip',
  buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"settings_company_infoaction.php",
    type:"POST",
    data:{action:'listCompany'},
    dataType:"json"
  },
  "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  "pageLength": 20
});


$('#addcompany').click(function(){
     $('#companyModal').modal('show');
    $('#companyForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add");
    $('#action').val('addCompany');
    $('#save').val('Add');
  });   




$("#company-info").on('click', '.update', function(){
  var companyId = $(this).attr("id");
  var action = 'getCompany';
  $.ajax({
    url:'settings_company_infoaction.php',
    method:"POST",
    data:{companyId:companyId, action:action},
    dataType:"json",
    success:function(data){
      $('#companyModal').modal('show');
      $('#companyId').val(data.id);
      $('#companyName').val(data.concept_name);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit");
      $('#action').val('updateCompany');
      $('#save').val('Save');
    }
  })
 }); 

$("#companyModal").on('submit','#companyForm', function(event){
  event.preventDefault();
  $('#save').attr('disabled','disabled');
  
  var formData = $(this).serialize();
  
  $.ajax({
    url:"settings_company_infoaction.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#companyForm')[0].reset();
      $('#companyModal').modal('hide');        
      $('#save').attr('disabled', false);
      companyinfo.ajax.reload();
    }
    })

})
  

$("#company-info").on('click', '.delete', function(){
  var company = $(this).attr("id");   
  var action = "companyDelete";
  if(confirm("Are you sure you want to delete this Concept?")) {
    $.ajax({
      url:"settings_company_infoaction.php",
      method:"POST",
      data:{company:company,action:action},
      success:function(data){          
       companyinfo.ajax.reload();
      }
    })
  } 
 
});

});

$(document).ready(function(){
var catinfo = $('#table-cat').DataTable({
  "lengthMenu": [[200, 75, 50, -1], [100, 75, 50, "all"]],
   "pageLength": 200,
  dom: 'Bfrtip',
  buttons: [
     'pageLength',
            
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0,1]
                }
            },
            {
                extend: 'excelHtml5',
                
                exportOptions: {
                 
                  columns: [0,1]
                   
                }
            },
             {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0,1]
                  }
                },
            
        'print'
        ],
  "charset": 'utf-8',
  "lengthChange": false,
  "processing":true,
  //"paging": false,
  "serverSide":true,
  "order":[],
  //"ordering":false, 
  //"searching":false,
  "ajax":{
    url:"categories_infoaction.php",
    type:"POST",
    data:{action:'listCategories'},
    dataType:"json"
  },
   "columnDefs":[
    {
      "targets":[],
      "orderable":false,
    },
  ],
  
});



$("#table-cat").on('click', '.add_subcat', function(){
      var subcatId1 = $(this).attr("id");
     $('#subcatIdAdd').val(subcatId1);
   
     $('#subcatModal').modal('show');
    $('#subcatForm')[0].reset();
    $('.modal-title').html("<i class='fa fa-plus'></i> Add Subcategorye");
    $('#action').val('addSubcat');
    $('#save').val('Add');
  });   




$("#table-cat").on('click', '.update_subcat', function(){
  var subcatId = $(this).attr("id");
  var action = 'getSubcat';
 
  $.ajax({
    url:'categories_infoaction.php',
    method:"POST",
    data:{subcatId:subcatId, action:action},
    dataType:"json",
    success:function(data){
      $('#subcatModal').modal('show');
      $('#subcatId').val(data.id);
      $('#subcatName').val(data.name);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit Subcategorie");
      $('#action').val('updateSubcat');
      $('#save').val('Save');
    }
  })
 });
 
 $("#table-cat").on('click', '.update_cat', function(){
  var subcatId = $(this).attr("id");
  var action = 'getCat';
  console.log(action);
  $.ajax({
    url:'categories_infoaction.php',
    method:"POST",
    data:{subcatId:subcatId, action:action},
    dataType:"json",
    success:function(data){
      $('#subcatModal').modal('show');
      $('#subcatId').val(data.id);
      $('#subcatName').val(data.name);
      $('.modal-title').html("<i class='fa fa-plus'></i> Edit Categorye");
      $('#action').val('updateCat');
      $('#save').val('Save');
    }
  })
 }); 


$("#subcatModal").on('submit','#subcatForm', function(event){
  event.preventDefault();
  $('#save').attr('disabled','disabled');
  
  var formData = $(this).serialize();
  
  $.ajax({
    url:"categories_infoaction.php",
    method:"POST",
    data:formData,
    success:function(data){       
      $('#subcatForm')[0].reset();
      $('#subcatModal').modal('hide');        
      $('#save').attr('disabled', false);
      catinfo.ajax.reload();
    }
    })

})

function validate_del_subcat(subcatId1) {
    var actionDel = 'subcatDeleteRes';
    

    return $.ajax({
        url: 'categories_infoaction.php',
        method: 'POST',
        data: { subcatId1: subcatId1, actionDel: actionDel },
         dataType:"json",
         success: function(data){
             
              

            }

    });
}

$("#table-cat").on('click', '.delete_subcat', function(){
  var subcatId1 = $(this).attr('id');
 
    var validate_items = validate_del_subcat(subcatId1);
               validate_items.success(function (data){
              var item_val = data.data;
              

             
        if (item_val === true) {
            alert('These products: '+data.info+ 'are assigned to this subcategory');

        } else {

            if (confirm('Are you sure you want to delete this subcategory?')) {
                var action = 'subcatDelete';
                $.ajax({
                    url: 'categories_infoaction.php',
                    method: 'POST',
                    data: { subcatId1: subcatId1, action: action },
                     dataType:"json",
                    success: function(data) {
                       catinfo.ajax.reload();
                       console.log('data');
                    }

                });
            }
        }
       
    })
})



function validate_del_cat(subcatId1) {
    var actionDel = 'catDeleteRes';
    

    return $.ajax({
        url: 'categories_infoaction.php',
        method: 'POST',
        data: { subcatId1: subcatId1, actionDel: actionDel },
         dataType:"json",
         success: function(data){
             return data;
            }

    });
}

$('#table-cat').on('click', '.delete_cat', function() {
    var subcatId1 = $(this).attr('id');
   
    var validate_items = validate_del_cat(subcatId1);
               validate_items.success(function (data){
             
              var item_val = data;
              console.log(item_val);
              var item_val_array2 = data.data;
              
              if (item_val_array2 === true){
                alert('These products: '+data.info+ ' are assigned to this category');
              }
             
        if (item_val === true) {
            alert('This category cannot be deleted because it has subcategories');

        } if (item_val === false) {

            
            if (confirm('Are you sure you want to delete this category?')) {
                var action = 'catDelete';
                $.ajax({
                    url: 'categories_infoaction.php',
                    method: 'POST',
                    data: { subcatId1: subcatId1, action: action },
                     dataType:"json",
                    success: function(data) {
                        catinfo.ajax.reload();
                    }
                });
            }
        
        }
    })
})

});



