(function(){
    const graph_data = {
      labels: [],
      data: [],
      month: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
    };

    var d = new Date("2020-08-02 23:18:11");


    // loading footer when page loads

    const user_data = {
        name : "",
        username : "",
        userType: "",
        email : "",
        img : "",
        current_cc: "",
        load : getData

    }

    const cc_data = {
        min_cc : 100,
        max_cc : null,
        cc_to_withdraw : null,
        cc_withdraw_rate : 0.47
    }

    const process_new_request = {
        status : false,
        processing_id : null,
        cc_to_withdraw : 100,
        account_no : null,
        re_account_no : null,
        ifsc : null,
        contact: null,
        flag : true                                     //to press redeem button once
    }

    const pendingSettlement = {
        status : false,
        process_id : null,
        cc_redeem : null,
        date: null,
    }

    const pin_obj = {
        flag : true,
        pin : null,
        process: function(){
            
            if(pin_obj.flag)
            {
                pin_obj.flag = false;
                if(pin_obj.pin != null)
                {
                    // pin_obj.flag = false;

                    $.ajax({
                        type: "post",
                        url: "https://www.codegreenback.com/mw/withdraw_mw.php",
                        data: {
                            data: JSON.stringify({
                                pin : pin_obj.pin,
                                processing_id : process_new_request.processing_id
                                }),    
                            _ser : "_pin",
                            _t : $("#token_csrf").val(),
                        },
                        beforeSend: function(){
                            $(".spinner1").show();
                        },
                        complete: function(){
                            $(".spinner1").hide();
                        },
                        headers: {
                        Authorization: "Bearer " + localStorage.getItem("sid"),
                        },
                        dataType: "json",
                        success: function (result) {
                            console.log(result);
                            if(result.status == -1)
                            {
                                $("#w-pin-msg").text(`Incorrect Pin Entered !!`);
                                $("#w-pin-msg-div").fadeIn();
                                setTimeout(function () {
                                        $("#w-pin-msg-div").fadeOut();
                                        $("#w-pin-msg").text("");
                                    }, 4000);
                                pin_obj.flag = true;
                            }
                            else if(result.status)
                            {
                                $("#wrapper").hide();
                                customAlert("Withdrawal Request Placed Successfully !! Note: The request will take 2-3 working days to be processed");

                            }
                            else
                            {
                                pin_obj.flag = true;
                                $("#wrapper").hide();
                                customAlert("An Error Occurred !!");
                            }
                        },
                    });
                }
            }
        }
    }

    
    $(document).ready(function(){
        user_data.load();
        $("#w-loader").hide();
        $(".container-redeem").fadeIn();
    });


    function getData()
    {
        $.ajax({
            type: "post",
            url: "https://www.codegreenback.com/mw/withdraw_mw.php",
            data: {
            _ser : "_gD",
            _t : $("#token_csrf").val(),
            },
            headers: {
            Authorization: "Bearer " + localStorage.getItem("sid"),
            },
            dataType: "json",
            success: function (result) {
                makePage(result);
                make_graph_data(result.ccstats);
            },
        });
    }

    


    function makePage(data)
    {
        $("#w-name").text(`${data.userData.name}`);
        $("#w-email").text(`${data.userData.email}`);
        $("#w-userimg").attr('src',`https://www.codegreenback.com/${data.userData.img_url}`);
        $("#w-usercc").text(`${data.cc}`);
        $("#w-new-processing-id").text(`${data.processing_id}`);

        user_data.name = data.userData.name;
        user_data.email = data.userData.email;
        user_data.img  = data.userData.img_url;
        user_data.username = data.userData.username;
        user_data.current_cc = data.cc;

        pendingSettlement.status = data.past_trans;
        if(pendingSettlement.status)
        {
            pendingSettlement.process_id = data.past_trans_data.processing_id;
            pendingSettlement.cc_redeem = data.past_trans_data.cc_redeem;
            pendingSettlement.date = data.past_trans_data.date;

            $("#display-setlement").html(`<table>
                    <tr class='w-pending-tr'>
                    <td>Processing Id : </td>
                    <td>${pendingSettlement.process_id}</td>
                    </tr>
                    <tr class='w-pending-tr'>
                    <td>CodeCoins : </td>
                    <td>${pendingSettlement.cc_redeem}</td>
                    </tr>
                    <tr class='w-pending-tr'>
                    <td>Date : </td>
                    <td>${pendingSettlement.date}</td>
                    </tr>
                    </table>`);
        }

        if(data.cc < 100)
        {
            cc_data.max_cc = 100;
            $("#w-cc-slider").attr("max", 100);
            $("#w-cc-exchange").text("-");
            $("#w-service-charge").text("-");
            $("#w-redeem-cc").text("-");
        }
        else
        {
            cc_data.max_cc = data.cc;
            $('#w-cc-slider').attr('max', data.cc);
        }

        if(data.status)
        {
            process_new_request.status = true;
            process_new_request.processing_id = data.processing_id;

        }



        /**
         * make buttons work
         */

        //slider
        slider = document.getElementById("w-cc-slider");
        slider.oninput = sliderWork;

        //redeem button
        
        $(".pay-btn").click(function(e){
            e.preventDefault();
            if(process_new_request.flag)
            {
                process_new_request.flag = true;
                if(validate())
                {
                    //send data to server
                    process_new_request.account_no = $('#w-acc-no').val();
                    process_new_request.re_account_no = $("#w-r-acc-no").val();
                    process_new_request.ifsc = $("#w-ifsc").val();
                    process_new_request.contact = $("#w-phone").val();

                    makeNewRequest();
                }
                else
                {
                    process_new_request.flag = true;
                }
            }
            
        });
    }


    function make_graph_data(data)
    {
        for(var i = 0 ; i < data.length ; i++)
        {
            var d = new Date(data[i].date);
        
            graph_data.labels[i] = d.getDate() + " " + graph_data.month[d.getMonth()];
            graph_data.data[i] = data[i].balance;
        }

        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: graph_data.labels,
            datasets: [{
                label: 'CodeCoins',
                data: graph_data.data,
                backgroundColor:['rgba(255, 255, 255,0)'],
            
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    // 'rgba(54, 162, 235, 1)',
                    // 'rgba(255, 206, 86, 1)',
                    // 'rgba(75, 192, 192, 1)',
                    // 'rgba(153, 102, 255, 1)',
                    // 'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            layout : {
                left: 50,
                right: 20,
                top: 50,
                bottom: 0
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
        });
    }

    function makeNewRequest()
    {
        $.ajax({
            type: "post",
            url: "https://www.codegreenback.com/mw/withdraw_mw.php",
            beforeSend:function(){
                $(".overlay-loader-w").show();
            },
            complete:function(){
                $(".overlay-loader-w").hide();
            },
            data: {
            data: JSON.stringify(process_new_request),    
            _ser : "_process",
            _t : $("#token_csrf").val(),
            },
            headers: {
            Authorization: "Bearer " + localStorage.getItem("sid"),
            },
            dataType: "json",
            success: function (result) {

                if(result.status == 1)
                {
                    process_pin();
                }
            },
        });
    }

    

 

    function process_pin()
    {
        $("#wrapper").fadeIn();

        $("#w-verify").click(function(e){
            e.preventDefault();
            
            let pin = getPin();
            
            if(pin.length != 6)
            {
                $("#w-pin-msg").text(`6 Digit Pin Required`);
                $("#w-pin-msg-div").fadeIn();
                setTimeout(function () {
                        $("#w-pin-msg-div").fadeOut();
                        $("#w-pin-msg").text("");
                    }, 4000);

            }
            else
            {
                if(!isNaN(parseInt(pin)))
                {
                    
                    pin_obj.pin = pin;
                    pin_obj.process();                                 //process verification
                }
                else
                {
                    console.log("sorry");
                }
            }
        });
    }

    

    function validate()
    {
        if($("#w-acc-no").val() == '')
        {
            customAlert("Account Number Is Required !!");
            return false;
        }

        if($("#w-r-acc-no").val() == '')
        {
            customAlert("All fields are required !!");
            return false;
        }

        if($("#w-acc-no").val() !== $("#w-r-acc-no").val())
        {
            customAlert("Account Number Do Not Match !!");
            return false;
        }

        let reg_num = new RegExp("^\\d{9,18}$");
        let reg_alphanumeric = /[A-Z|a-z]{4}[0][a-zA-Z0-9]{6}$/;
        let reg_phone = new RegExp("\\d{10}$");

        if(!reg_num.test($("#w-acc-no").val()))
        {
            customAlert("Please Provide A Valid Account Number ");
            return false;
        }

        if(!$("#w-ifsc").val().match(reg_alphanumeric))
        {
            customAlert("Please Provide A Valid IFSC Code");
            return false;
        }

        if(!reg_phone.test($("#w-phone").val()) || $("#w-phone").val().length != 10)
        {
            customAlert("Please Provide A Valid Contact Number !!");
            return false;
        }
        
        /**
         * check whether there is a pending settlement
         */
        if(pendingSettlement.status)
        {
            customAlert("You Cannot Redeem Code Coins Until All The Pending Settlements Are Cleared");
            return false;
        }

        if(parseInt(user_data.current_cc) < parseInt(process_new_request.cc_to_withdraw))
        {
            customAlert(`You Don't Have ${process_new_request.cc_to_withdraw} CodeCoins !!`);
            return false;
        }

        if(user_data.userType == 'non-elite')
        {
            customAlert("Become An Elite Member To Redeem CodeCoins");
            return false;
        }

        return true;
    }


    function sliderWork()
    {
        $("#w-cc-exchange").html(`&#8377; ${(0.47 * $("#w-cc-slider").val()).toFixed(2)}`);

        if($("#w-cc-slider").val() > 1000)
        {
            $("#w-service-charge").html(`- &#8377;5.00`);
            $("#w-redeem-cc").html(`&#8377;${((0.47 * $("#w-cc-slider").val())-5.00).toFixed(2)}`);
        }
        else
        {
            $("#w-service-charge").html(`- &#8377;2.50`);
            $("#w-redeem-cc").html(`&#8377;${((0.47 * $("#w-cc-slider").val())-2.50).toFixed(2)}`);
        }

        $("#rangeValue").text(`${$("#w-cc-slider").val()}`);

        process_new_request.cc_to_withdraw = $("#w-cc-slider").val();

    }


                //  to display custom msg

    function customAlert(msg) 
    {
        // Get the modal
        let modal = document.getElementById(
            "customAlert"
        );

        $("#customAlertMsg").text(msg);
        // modal.style.display = "block";

        $("#customAlert").fadeIn();

        $(".customClose").click(function()
        {
            modal.style.display = "none";
        });
                                        

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
            modal.style.display = "none";
            }
        };
    };

    function getPin()
    {
        var pins = document.querySelectorAll(".pin-inputs");
        let pin = pins[0].value.concat(
            pins[1].value,
            pins[2].value,
            pins[3].value,
            pins[4].value,
            pins[5].value
        );

        return pin;

        
    }




    // ......................................pin.................................//
    var body = $('body');

  function goToNextInput(e) {
    var key = e.which,
      t = $(e.target),
      sib = t.next('.pin-inputs');

    if (key != 9 && (key < 48 || key > 57)) {
      e.preventDefault();
      return false;
    }

    if (key === 9) {
      return true;
    }

    if (!sib || !sib.length) {
    sib = body.find('.pin-inputs').eq(0);
        
    }
    sib.select().focus();
  }

  function onKeyDown(e) {
    var key = e.which;

    if (key === 9 || (key >= 48 && key <= 57)) {
      return true;
    }

    e.preventDefault();
    return false;
  }
  
  function onFocus(e) {
    $(e.target).select();
  }

  body.on('keyup', '.pin-inputs', goToNextInput);
  body.on('keydown', '.pin-inputs', onKeyDown);
  body.on('click', '.pin-inputs', onFocus);


})();




