    var selected_month = new Date().getMonth()+1;
    var selected_year = new Date().getFullYear();
$( document ).ready(function() {

    var status_class = {
        frozen : 'default',
        registered : 'success',
        pending : 'warning'
    }

    add_totals();

    var url = "http://site.com/"

    var img_list = []
    // RENTALS

    $(".reserve").click(function(){
        console.log("HI");

    })

    $("#rental-modal").delegate(".rates-table td", "keyup", function(){

        if($(".rates-table tr:last").find('td:not(:first):not(:empty)').length){
            var row = "<tr><td><input type=\"checkbox\"></td>";
            for(var i = 0; i < 6; i++){
                row += "<td contenteditable></td>"
            }
            row += "</tr>"
            $(".rates-table tbody").append(row)
        }
    })

    $("#rental-modal").delegate(".delete-rates", "click", function(e){
        e.preventDefault();
        console.log("WHY")
        $(".rates-table tbody tr:not(:last)").find('input[type="checkbox"]:checked').each(function(){
            $(this).closest("tr").remove()
        })
    })

    $(".rental-table").delegate(".edit-rental", "click", function(){
        var id = $(this).closest('tr').data('id');
        $("#rental-modal .modal-body").load(baseurl+'/admin/rental_test', function(){
            $.post(baseurl+'/admin/get_rental/'+id,
                    function(result){
                        
                        $("#rental-modal-label").text("Edit Rental");
                        $("#rental-form input[name=type]").val('edit');

                        $.each(result, function(index, val){
                            console.log(index+" "+val);
                            $("#rental-modal [name="+index+"]").val(val);
                        })
                        console.log("rates:" + result.rates);
                        if(result.rates)
                            build_table(result.rates)
                        if(result.images != ""){
                            var images = result.images.split(",")
                            img_list = images
                            $.each(images, function(key, value){
                                add_thumbnail(result.img_path+value, value, (value == result.thumbnail));
                            })
                        }
                        
                    },'json');
            $("#rental-modal").modal('show');
        })
        
    })

    $(".rental-table").delegate(".delete-rental", 'click', function(){
        console.log("hi")
        var row = $(this).closest('tr')
        var id = row.data('id');
        var msg = "<p>Are you sure you want to delete this rental?</p>";
        confirmation_modal("Confirm Delete", msg, "Delete", function(){
            $.post(baseurl+'/admin/delete_rental/'+id,
                function(result){
                    row.slideUp("slow",function(){
                        $(this).remove();
                    });
                }
            )
        })
    })

    function build_table(table){
        table = table.split(";").join("</td><td contenteditable>")
        table = table.split("|").join("</td></tr><tr><td><input type=\"checkbox\"></td><td contenteditable>")
        // console.log("semicolons:"+table)
        // table = table.replace("|", "</td></tr><tr><td contenteditable>")
        // console.log("pipes:"+table)
        console.log("<tr><td contenteditable>"+table+"</td></tr>")
        $(".rates-table tbody").prepend("<tr><td><input type=\"checkbox\"></td><td contenteditable>"+table+"</td></tr>");
    }

    $("#rental-save").on("click", function(e){

        e.preventDefault();
        
        $("#rental-modal input[name=imgs").val(img_list.join(","))

        var rates_rows = $('.rates-table tbody tr');

        // convert table to string (rows separated by | and cols separated by ;)
        var rates_table = $(rates_rows).get().map(function(row){
            return $(row).find('td:not(:first)').get().map(function(cell){
                return $(cell).html();
            }).join(";")
        }).join("|")

        // add rates table string to corresponding input element, but remove the last row because it's always empty
        $("#rental-modal input[name=rates").val(rates_table.substring(0,rates_table.length-6));
        $("#rental-form").submit();
    })

    $('#rental-modal').on('hidden.bs.modal', function (e) {
        $(this).removeData('modal');
    })

    $("#rental-modal").delegate(".delete-img", "click", function(e){
        e.preventDefault();
        var remove_img = $(this).prev().find('img').prop('alt')
        var index = img_list.indexOf(remove_img)
        if(index > -1)
            img_list.splice(index,1)
        console.log(img_list)
        $(this).closest('.col-md-4').fadeOut(500, function(){
            this.remove()
        })

    })

    $("#rental-modal").delegate(".thumbnail > img", "click", function(e){
        e.preventDefault();
        // set main-img input to currently selected image name
        var imgsrc = $(this).prop('alt');
        $(".main-img").val(imgsrc);

        // remove border from previously selected image and add it to currently selected image
        $("a.selected-img").removeClass("selected-img")
        $(this).parent().addClass("selected-img");
    })

    function add_thumbnail(src, name, main){
        var row = '<div class="col-md-4 show-img"><a href="#" class="thumbnail '+(main ? 'selected-img' : '') +'"><img src="'+src+'" alt="'+name+'"></a><button title="delete" class="btn btn-danger btn-xs delete-img"><span class="glyphicon glyphicon-remove"></span></button></div>'
        $(".thumbnails").append(row);
        console.log(img_list)
    }

    var filelistener = function(){
        console.log("FILES HAS BEEN CLICKED");
        var label = $(this).parent()
        $(this).parent().parent().prepend(label.clone())
        label.css({'position':'absolute','left':'-9999px','display':'none'})

        var files = this.files;
        $.each(files, function(){
            if(this.type.match('image.*')){
                var reader = new FileReader()
                reader.onload = (function(theFile){
                    return function(e){
                        img_list.push(theFile.name)
                        add_thumbnail(this.result, theFile.name)

                    };
                })(this);
                reader.readAsDataURL(this);
            }

        })
    }

    $("#rental-modal").delegate('#files', 'change', filelistener)



        $(".add-rental").click(function(){
        // $("#rental-modal").modal('show');
        var url = $(this).data('url')
        img_list = []
        console.log(img_list)
        $("#rental-modal .modal-body").load(url, function(){
            // $("#rental-modal").click(filelistener);
            $("#rental-modal-label").text("Add Rental");
            $("#rental-form input[name=type]").val('add');
        })
        
    })

    $("#arrival").datepicker();
    $("#depart").datepicker();

    $("#rental").val($(".rental-name").html())

    $(".contact-submit").click(function(e){
        e.preventDefault();
        var form = $(".contact-form")
        if(validate(".contact-form")){
            console.log("what")
            $(".contact-form input[name=Rental]").val($("h2").html());
            $.post(form.attr('action'),
                form.serialize(),
                function(){
                    console.log("HI")
                    $("#contact-modal").modal('hide');
                });
        }
    })


    // EXPENSES

    $('#expenses_table').delegate(".edit-expense", 'click', function(){
        $("#expense-modal").modal('show');
        $("#expense-modal-label").text("Edit Expense");
        $("#expense-form input[name=type]").val('edit');

        var row = $(this).closest('tr');
        $("#expense-modal input[name=id]").val($(this).closest('tr').data('id'));
        $("#expense-modal input[name=date-display]").val(row.children(':nth-child(1)').text());
        $("#expense-modal input[name=date]").val($.datepicker.formatDate("yy-mm-dd", new Date(row.children(':nth-child(1)').text())));
        $("#expense-modal select[name=category]").val(row.children(':nth-child(2)').text());
        $("#expense-modal select[name=tags]").val(row.children(':nth-child(3)').text());
        $("#expense-modal input[name=description]").val(row.children(':nth-child(4)').text());
        $("#expense-modal input[name=amount]").val(row.children(':nth-child(6)').text().replace("$",""));
        $("#expense-modal input:radio[name=currency]").val(["usd"]);

        $("#expense-save").on("click", function(){
            if(validate('#expense-form')){
                $(this).unbind('click');
                update_expenses(edit_expense_row);
            }
        })
    })

    $("#expense-form").delegate(".has-error input", "keyup",function(){
        $(this).closest(".form-group").removeClass('has-error');
    })

    $("#expense-form input[name=amount]").blur(function(){
        $(this).val(parseFloat(this.value.replace(/,/g,"")).toFixed(2).toString())
    })

    $(".add-expense").click(function(){
        $("#datepicker").datepicker("option", "defaultDate", new Date(selected_year, selected_month-1, 1));
        $("#expense-modal").modal('show');
        $("#expense-modal-label").text("Add Expense");
        $("#expense-form input[name=type]").val('add');
        $("#expense-save").on("click", function(){
            if(validate('#expense-form')){
                $("#expense-save").unbind('click');
                update_expenses(create_expense_row);
            }
            // $("#expense-modal").modal('hide');
        })
    })

    $("#expenses_table").delegate(".view-receipt", 'click', function(){
        var desc = $(this).closest('tr').children(':nth-child(4)').text();
        $(".modal-title").text("Receipt for " + desc);
        $("#image-modal").modal('show');
        var expense_id = $(this).closest('tr').data('id');
        var img = $(".receipt");
        $(img).attr('src',$(img).data('src')+"/"+expense_id);
    })

    function validate(formname){
        console.log("validate");
        var submit = true;
        // console.log($(formname).serialize())
        $(formname+" .required").each(function(){
            if($(this).val() === ""){
                console.log(this);
                submit = false;
                $(this).closest('.form-group').addClass('has-error');
            }
            else
                $(this).parent().removeClass('has-error');
        })
        if(submit){
            return true;
        }
        else{
            $(".errors").html("Please fill out the required fields");
            console.log('nope');
            return false;
        }
    }

    $(".table").delegate(".delete-expense","click", function(event){
        event.preventDefault();
        var row = $(this).closest('tr');
        var id = row.data('id');
        var msg = "<p>Are you sure you want to delete this expense?";
        confirmation_modal("Delete Expense", msg, "Delete", function(){
            $.post(baseurl+'/admin/delete_expense',
                {'id':id, 'month':selected_month, 'year':selected_year},
                function(result){
                    var numrows = $("#expenses_table > tbody > tr:visible").length;
                    if(numrows == 1){
                        $("#no-results").show();
                        $("#expenses_table").hide();
                    }
                    else{
                        $(row).remove();
                    }
                }
            );
        })
    })

    $("#datepicker").datepicker({altFormat: "yy-mm-dd", altField: "#date",onSelect: function(){
            $(this).closest(".form-group").removeClass('has-error');
        }
    });

    $("select[name=download-when]").click(function(){
        if($(this).val() == 'range')
            $("#range-div").removeClass('hidden');
        else
            $("#range-div").addClass('hidden');
    })

    $("#download").submit(function(event){
        event.preventDefault();
        console.log($(this).serialize());
        $.post(baseurl+'/user/zip_expenses',
            $(this).serialize(),
            function(result){
                console.log(result);
            }
        ).fail(function(){
            // TODO: DISPLAY ERROR MODAL
            location.reload();
        });
    })

    $("#month").change(function(){
        selected_month = $(this).val();
        get_data();
    });

    $("#year").change(function(){
        selected_year = $(this).val();
        get_data();
    });

    $("#category").change(filter_table);
    $("#tag").change(filter_table);


    $(".table").delegate(".upload-receipt", "click", function(){
        $("#receipt-modal").modal('show');
        var id = $(this).closest('tr').data('id');
        $("#receipt-form input[name=id]").val(id);
    })

    // REGISTER

    $("#register").submit(function(event){
        var submit = true;
        $("#register input[type=password]").each(function(){
            if($(this).val() === ""){
                submit = false;
                $(this).addClass('error-input');
            }
            else
                $(this).removeClass('error-input');
        })
        if(submit){
            if($("#password").val() !== $("#passwordconf").val()){
                submit = false;
                $(".alert-danger").text("Passwords do not match").removeClass('hidden');
                event.preventDefault();
            }
            else{
                $(".alert-danger").addClass('hidden');
            }
        }
        else{
            event.preventDefault();
        }
    })

    // USERS

    $("#users-table").delegate(".freeze", "click", function(event){
        event.preventDefault();
        var id = $(this).closest('tr').data('id');
        var button = $(this);
        $.post(baseurl+'/admin/toggle_freeze/'+id,
            function(result){
                var isFrozen = parseInt(result.frozen);
                var button_text = isFrozen ? "Freeze" : "Unfreeze";
                var status_text = isFrozen ? result.status : "frozen";
                console.log(status_text);
                $(button).text(button_text);
                $(button).closest('tr').children(':nth-child(2)').html('<span class="label label-'+status_class[status_text]+'">'+status_text+'</span>')
            },'json').fail(function(){
                console.log("FAIL");
            })
    })

    $("#users-table").delegate(".resend", "click", function(event){
        event.preventDefault();
        var id = $(this).closest('tr').data('id');
        var name = $(this).closest('tr').children(':nth-child(1)').find('strong').html();
        var msg = "<p>Are you sure you want to resend an email to <strong>"+name+"</strong>?";
        confirmation_modal("Resend Email", msg, "Send Email", function(){
            $.post(baseurl+'/admin/reset_registration/'+id);
        })
    })

    function confirmation_modal(title, message, action, action_func){
        $("#confirm-modal .modal-title").html(title);
        $("#confirm-modal .modal-body").html(message);
        var action_button = $("#confirm-modal #confirm-action");
        $(action_button).html(action).unbind("click");
        $("#confirm-modal").modal('show');
        $(action_button).one('click', function(){
            action_func();
            $("#confirm-modal").modal('hide');
        });
    }

    // CONTRACT

    $(".delete-contract").click(function(){
        var msg = "<p>Are you sure you want to delete this contract?";
        var id = $(this).parent().data('id');
        var button = $(this);
        confirmation_modal("Delete Contract", msg, "Delete", function(){
            $.post(baseurl+'/admin/delete_contract/'+id,
                function(result){
                    console.log(result);
                    $('.img-responsive').remove();
                    $(button).parent().append('<button class="btn btn-success btn-lg add-contract" data-toggle="modal" data-target="#contract-modal"><span class="glyphicon glyphicon-plus"></span> Upload</a>')
                    $(button).remove();
                }
            )
        })
    })


    $("#view-contract").click(function(){
        $.post(baseurl+'/user/view_contract',
            function(result){
                console.log(result);
                if(result.length === 0){
                    console.log("no contract");
                }
                else{
                    console.log("contract");
                }
            }
        ).fail(function(){
            // TODO: DISPLAY ERROR MODAL
            location.reload();
        });
    })

    // MANAGE FILTERS
    $(".list-group").delegate(".btn-danger","click",function(event){
        event.preventDefault();
        var listitem = $(this).parent();
        var type = $(this).closest('.panel').data('type');
        var text = $(this).parent().text();
        var msg = "<p>Are you sure you want to delete <span class=\"text-danger\">"+text+"</span>?</p>";
        confirmation_modal("Delete "+type, msg, "Delete", function(){
            $.post(baseurl+'/admin/delete_filter',
                {'type':type, 'text':text},
                function(result){
                    listitem.slideUp(function(){
                        $(this).remove();
                    });
                }
            )
        })
    })

    $(".add-filter").click(function(event){
        event.preventDefault();
        var listgroup = $(this).closest(".panel-body").prev();
        console.log(listgroup)
        var text = $(this).parent().prev().val();
        var type = $(this).closest('.panel').data('type');
        if(text !== ""){
            $.post(baseurl+'/admin/add_filter',
            {'type': type, 'text':text},
            function(result){
                console.log(result);
                $("<li class=\"list-group-item\" style=\"display:none\">"+text+"<button title=\"delete\" class=\"btn btn-danger btn-xs pull-right\"><span class=\"glyphicon glyphicon-trash\"></span></button></li>").appendTo(listgroup).slideDown();
            }
            ).fail(function(){
                console.log("FAILURE");
            })
        }
        
    })

    $(".modal").on('hide.bs.modal', function(){
        console.log("HIDDEN NOW");
    })

    $("body").on('hidden.bs.modal', '.modal', function(){
        console.log("HIIIIII I'm HIDDEN");
        // console.log($("#confirm-action"));
        // $('.modal input[type=text]').val('');
        // $('.modal select').each(function(){
        //     $(this)[0].selectedIndex = 0;
        // })
        // $('.errors').html('');
    })

    $(".modal").on('hidden.bs.modal', function(){
        console.log("HIIIIII I'm HIDDEN");
        // console.log($("#confirm-action"));
        // $('.modal input[type=text]').val('');
        // $('.modal select').each(function(){
        //     $(this)[0].selectedIndex = 0;
        // })
        // $('.errors').html('');
    })

    $(".add-user-submit").click(function(event){
        event.preventDefault();
        if(validate('#add-user')){
            console.log(baseurl);
            $.post(baseurl+'/admin/add_pending_user',
                $('#add-user').serialize(),     
                function(result){
                    console.log(result)
                    if(result.error)
                        $(".alert").removeClass('hidden').html(result.error);
                    else{
                        $(".table tbody").append(pending_user_row(result.name, result.email,result.user_id))
                        $("#add-pending-user").modal('hide');
                    }
                },
                'json')
            .fail(function(){
                location.reload();
            })
        }
        else{
            $(".alert").removeClass('hidden').html("Please fill out the required fields");
        }

        // $.post(baseurl+'/admin/add_pending_user',
        //     $(this).serialize(),
        //     function(result){
        //         if(result.error)
        //             $(".errors").html(result.error);
        //         else
        //             $(".success").html(result.success);
                
        //     },'json'
        // ).fail(function(){
        //     console.log("FAILURE");
        //     // TODO: DISPLAY ERROR MODAL
        //     //location.reload();
        // });
    })

    $("th").click(function(){
        var type = $(this).data("type"),
            is_asc = $(this).data("asc"),
            index = $(this).index()+1,
            rows = $(".table > tbody > tr:visible");
        $('.table th.active').removeClass('active').removeClass("asc").removeClass("desc");
        $(this).addClass('active');
        $(this).addClass(is_asc ? "asc" : "desc");
        function get_sort(type, is_ascending){
            var sort_fn;
            function default_sort(a,b){
                return a < b ? 1 : a > b ? -1 : 0;
            }

            switch(type){
                case "string":
                    sort_fn = default_sort; 
                    break;
                case "float":
                    sort_fn = function(a,b){
                        a = parseFloat(a.replace("$", ""))
                        b = parseFloat(b.replace("$", ""))
                        return default_sort(a,b);
                    }
                    break;
                // TODO: change this when date format is decided on
                case "date":
                    sort_fn = default_sort;
                    break;
            }
            if(!is_ascending){
                var base_sort = sort_fn;
                sort_fn = function(a,b){return -base_sort(a,b)}
            }

            return function(a,b){
                var at_column = ":nth-child("+index+")";
                var td_a = $(a).children(at_column).text();
                var td_b = $(b).children(at_column).text();
                return sort_fn(td_a, td_b);
            }
        }
        rows.sort(get_sort(type, is_asc));
        $(".table tbody").html("").append(rows);
        $(this).data("asc", !is_asc);
    })




    // PROFILE

    var changes = {};

    $("input").change(function(){
        changes[$(this).attr("name")] = $(this).val();
    })

    $("#profile").submit(function(event){
        event.preventDefault();
        console.log(changes);
        var id = $(this).data('id');
        $.post(baseurl+'/user/update_profile/'+id,
            changes,
            function(result){
                console.log('success');
                console.log(result);
            }
        ).fail(function(){
            console.log('fail');
            // TODO: DISPLAY ERROR MODAL
            // location.reload();
        });
    })

});


function delete_filter(text, type){
    $.post(baseurl+'/admin/delete_filter',
        {'type':type, 'text':text},
        function(result){

        })
}

function populate_table(data, isadmin){
    $.each(data, function(index,expense){
        create_expense_row(expense, isadmin)
    })
    add_totals();
    filter_table();
}

function get_data(){
    var month_name = $("#month option:selected").text();
    var month = $("#month").val();
    var year = $("#year").val();

    var num = $("input[name=user]").val();
    var url = baseurl+'/user/update_table/'
    if(num)
        url += num
    $("input[name=current-month]").val(month);
    $("input[name=current-year]").val(year);
    $.post(url,
        {'month':month, 'year':year},
        function(result){
            console.log(result)
            $(".page-header").text("Expense Report for "+month_name+" "+year);
            $("#expenses_table > tbody").html("");
            if(result.length == 0){
                $("#expenses_table").hide();
                $("#totals").html("");
                $("#no-results").show();
            }
            else{
                $("#no-results").hide();
                $("#expenses_table").show();
                populate_table(result.expenses, result.admin);
            }
        }, "json"
    ).fail(function(){
        // TODO: DISPLAY ERROR MODAL
        console.log("FAIL");
        // location.reload();
    });
}

function filter_table(){
    var category = $("#category").val();
    var tag = $("#tag").val();
    $("#expenses_table").show();
    $("#expenses_table tbody tr").show();
    $("#expenses_table > tbody > tr").each(function(){
        if(category && $(this).find('td.'+'category').text() != category){
            $(this).hide();
        }
        if(tag && $(this).find('td.'+'tags').text() != tag){
            $(this).hide();
        }
    })
    var numrows = $("#expenses_table > tbody > tr:visible").length;
    if(numrows == 0){
        $("#no-results").show();
        $("#expenses_table").hide();
    }
    else{
        $("#no-results").hide();  
    }
}

function pending_user_row(name, email, id){
    return '<tr><td><strong>'+name+'</strong><br /><small>'+email+'</small></td><td><span class="label label-warning">pending</span></td><td><div class="btn-group"><a class="btn btn-default" href="http://localhost/codeigniter/index.php/admin/contract/'+id+'">Contract</a><a class="btn btn-default" href="http://localhost/codeigniter/index.php/admin/profile/'+id+'">Profile</a><a class="btn btn-default" href="http://localhost/codeigniter/index.php/admin/expenses/'+id+'">Expenses</a></div></td><td><div class="btn-group"><button class="btn btn-default freeze" data-id="'+id+'">Freeze</button><a class="btn btn-default" href="#">Resend Email</a></div></td></tr>';
}

function create_expense_row(expense, isadmin){
    var tr = $('<tr data-id="'+expense.id+'">').append(
        $('<td class="date">').text($.datepicker.formatDate("mm/dd/yy", new Date(expense.date))),
        $('<td class="category">').text(expense.category),
        $('<td class="tags">').text(expense.tags),
        $('<td class="description">').text(expense.description),
        $('<td class="amt_mxn">').text('$'+parseFloat(expense.amt_mxn).toFixed(2)),
        $('<td class="amt_usd">').text('$'+parseFloat(expense.amt_usd).toFixed(2)),
        $('<td>').html(expense.receipt_image ? '<button class="btn btn-default btn-sm view-receipt">View</button>' : isadmin ? '<button class="btn btn-default btn-sm upload-receipt">Upload</button>' : '')
    );
    if(isadmin)
        $(tr).append(
            $('<td>').html('<div class="btn-group btn-group-sm"><button class="edit-expense btn btn-default">Edit</button><button class="delete-expense btn btn-danger">Delete</button></div>')
        )
    $("#expenses_table > tbody").append(tr);
}

function edit_expense_row(expense, isadmin){
    var row = $('tr[data-id="' + expense.id + '"]');
    for(x in expense){
        console.log(expense[x]+" "+x)
        if(x.indexOf("amt") > -1)
            expense[x] = '$'+parseFloat(expense[x]).toFixed(2);
        $(row).find('td.'+x).text(expense[x]);
    }
}

function update_rentals(func){
    $("#rental-form").submit();
}

function update_expenses(func){
    if($("#expense-modal input[name=userfile]").val())
        $("#expense-form").submit();
    else{
        console.log($("#expense-form").serialize())
        $.post(baseurl+'/admin/process_expense', $("#expense-form").serialize(), 
            function(result){
                console.log(result);
                var expense_date = new Date(result.date);
                if(expense_date.getMonth()+1 == selected_month && expense_date.getFullYear() == selected_year){
                    func(result, 1);
                    filter_table();
                    add_totals();
                    // $("#expenses_table").show();
                }
                $("#expense-modal").modal('hide');

            }, 'json').fail(function(){
                console.log("FAILURE");
                // TODO: DISPLAY ERROR MODAL
                //location.reload();
            });
    }
}

function add_totals(){
    var total_usd = 0;
    var total_mxn = 0;
    $("#expenses_table > tbody > tr").each(function(){
        total_usd += parseFloat($(this).children(':nth-child(6)').text().replace("$",""));
        total_mxn += parseFloat($(this).children(':nth-child(5)').text().replace("$",""));
    })
    $("#totals").html("USD $"+total_usd.toFixed(2)+"<br />MXN $"+total_mxn.toFixed(2));
}