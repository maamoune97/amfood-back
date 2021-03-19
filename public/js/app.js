$(function(){
    
    /*
        *****************
        Interface Manager
        *****************
    */
   (function() 
   {
       async function getCommand(id) {
           return await axios.get(`/restaurant/manager/orders/${id}`).then(response => {
               return response;
           }).catch(error => {
               return error;
           });
       }
   
       //Recuperer les details d'une commande en attente
       $('.card-order').click(async function (e) {
           $(".details").hide();
           $(".details").removeClass('d-none');
           const commandId = $(this).data("id");
           const command = await getCommand(commandId);
        //    console.log(command);
        
           $('#showCmdNum').text(command.data.id)
           $('#showCmdPrice').text(command.data.totalPrice+' Fc')
           $('#acceptCmd').attr('href', command.data.acceptOrderUrl)
   
           const trPrototype = $('.show tbody').data("prototype");
   
           $('.show tbody').empty();
           command.data.articlePacks.forEach(articlePack => {
               
               // transfomation du array options en string si il y'en a
               let options = "";
               if (articlePack.options.length > 0) {
                   articlePack.options.forEach(option => {
                       options += option.optionName + ': ' + option.optionFieldName + ", ";
                   })
                   //suprimmer le dernier ', ' à la fin
                   options = options.slice(0,-2)
               }
   
               //Affichage des articlesPacks
               let tr = trPrototype.replace(/__article__/g, articlePack.article).replace(/__quantity__/g, articlePack.quantity).replace(/__options__/g, options).replace(/__price__/g, articlePack.price+' Fc');
               $('.show tbody').append(tr);
           })
           //afficher la commande une fois tout récuper
           $(".details").show();
       })

   }());

    /*
        *********************
        Fin Interface Manager
        *********************
    */

});
