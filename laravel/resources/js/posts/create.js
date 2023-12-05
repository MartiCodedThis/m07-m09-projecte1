// Load our customized validationjs library
import Validator from '../validator'


// Submit form ONLY when validation is OK
const form = document.getElementById("create-post-form")


if (form) {
   form.addEventListener("submit", function( event ) {
       // Reset errors messages
       // [...]
       // Get form inputs values
       let data = {
           "upload": document.getElementsByName("upload")[0].value,
           "body": document.getElementsByName("body")[0].value,
           "latitude": document.getElementsByName("latitude")[0].value,
           "longitude": document.getElementsByName("longitude")[0].value,
       }
       let rules = {
           "upload": "required",
           "body": "required",
           "latitude": "required",
           "longitude": "required",
       }
       // Create validation
       let validation = new Validator(data,rules)
       // Validate fields
       if (validation.passes()) {
           // Allow submit form (do nothing)
           console.log("Validation OK")
       } else {
           // Get error messages
           let errors = validation.errors.all()
           console.log(errors)
           // Show error messages
           let errorContainer = document.getElementById("error-container")
           for(let inputName in errors) {       
                var div = document.createElement("div");
                div.className = "p-5 text-[#664d03] bg-[#fff3cd] border border-[#ffecb5] rounded";
             
               let error = errors[inputName]
               let errormsg = document.getElementById("errormsg")
               div.innerHTML= "[ERROR] " + error

               errorContainer.appendChild(div);

               console.log("[ERROR] " + error)
               // [...]
           }
           // Avoid submit
           event.preventDefault()
           return false
       }
   })
}