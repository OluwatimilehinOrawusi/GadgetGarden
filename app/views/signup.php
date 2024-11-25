<!DOCTYPE html>
<html lang="en">
<head>
    <link href = "./styles/signup_page.css" rel = "stylesheet"/>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GADGET GARDEN </title>

</head>

<body>  

    <header>
       

        <nav>
            <h3>GADGET GARDEN</h3>

            <ul class="Menu2">
               
                <h6><a href = "./categories.html"><button>Categories</button></a></h6><br/>
                
                <h6><a href = "./webpage.html"><button>Webpage</button></a></h6><br/>
        
                <h6><a href = "./webpage1.html"><button>Webpage1</button></a></h6><br/>

                <h6><a href = "./webpage2.html"><button>Webpage2</button></a></h6><br/>
            </ul>
        </nav>
    </header>

    <div class = "webpage">

    <div class = "intro">
        <h2>Grow Your Tech Sustainably - Buy, Sell, and Renew at Gadget Garden!</h2>

        <div class="image1"> 


            <img src = "./images/ggLaptopSignIn.png" class = "images" alt = "Laptop Promo"/>

        </div>

        </div>  

        <div class = "signup">
            <h3>Create Account</h3>

            <form id ="myForm" onsubmit="return checkEmails(this)">

                <div class = "creating">    
                    <label>Username</label>
                    <input required type="text" id="name" placeholder="Username">

                    <label for="email">E-mail</label>

  <input required type="text" id="email1" name="Email" placeholder="Email">

  <label for="phonenumber">Phone Number</label>

  <input required ="number" id="phonenumber" name="Phone Number" placeholder="Phone Number">


  <label for="password">Password</label>

  <input required type = "password" id="password" name="Password" placeholder="Password">

  <label for="cpassword">Confirm Password</label>

  <input required ="text" id="cpassword" name="Confirm Password" placeholder="Confirm Password">

  <button type="createaccount">Create Account</button>

<h6>Already have an account? <a href = "./login_page.html">Log in</a></h6>

                </div>
</div>

            </form>
            </div>
</body>
</html>