<style>
    #main-content h3{
        padding-left: 1rem;
        margin-bottom: 0;
        text-transform: uppercase;
    }

    #status, #user, #admin{
        display: flex;
        flex-wrap: wrap;
    }

    .info-box{
        padding: 0.3rem 1rem;
        border-left: 4px solid;
        margin: 1rem;
        flex-basis: 45%;
        border-radius: 5px;
        box-shadow: 7px 7px 4px rgba(0, 0, 0, 0.25);
        background-color: white;
    }

    .heading{
        display: flex;
        justify-content: space-between;
    }
    .heading h5{
        color: white;
        text-align: center;
        padding: 0.5rem 1rem;
        flex-basis: 70%;
        border-radius: 0px;
        margin: 0.5rem 0;
    }

    .info-box p{
        margin: 0;
    }

    .info-content{
        margin-bottom: 1rem;
    }

    .info-content .num{
        font-size: 1.5rem;
    }

    .info-box a{
        display: block;
        text-align: right;
        text-decoration: none;
        font-weight: bold;
    }
    /* START-hardcoding */
    #Booking{
        border-color: #D2042D;
    }

    #Booking h5{
        background-color: #353935;
    }
    #Booking a{
        color: #800020;
    }


    #Bus {
        border-color: #D2042D;
    }

    #Bus h5{
        background-color: #353935;
    }

    #Bus a{
        color: #800020;
    }

    #Route{
        border-color: #D2042D;
    }

    #Route h5{
        background-color: #353935;
    }

    #Route a{
        color: #800020;
    }

    #Seat{
        border-color:#D2042D;
    }

    #Seat h5{
        background-color: #353935;
    }

    #Seat a{
        color: #800020;
    }

    #Customer{
        border-color: #D2042D;
    }

    #Customer h5{
        background-color: #353935;
    }

    #Customer a{
        color: #800020;
    }
	#Driver{
		border-color: #D2042D;
	}
	#Driver h5{
        background-color: #353935;
    }

    #Driver a{
        color: #800020;
    }

    #Admin{
        border-color: #D2042D;
    }

    #Admin h5{
        background-color: #353935;
    }

   

    #Admin a{
        color: #800020;
    }
    /* END-hardcoding */


    #admin .info-box{
        text-align: center;
        padding: 1rem 0;
        border: none;
    }

    #admin h4{
        margin: 0.5rem 0;
    }

    #admin img{
        border-radius: 50%;
    }


    @media only screen and (min-width:1000px){
        #main-content{
            flex-grow: 1;
        }

        .info-box{
            flex-basis: 20%;
        }

        #admin .info-box{
            flex-basis: 15%;
        }
    }
</style>