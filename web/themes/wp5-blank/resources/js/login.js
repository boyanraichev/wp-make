var login = {
	
	init: function() {
		let loginForm = document.getElementById('login-form');
		if (loginForm) {
			loginForm.addEventListener('submit',login.submit)
		}		
	},
	
	submit: function(e) {
		
		e.preventDefault();
	        
		let msg = this.querySelector('message');
		msg.innerHTML = this.ajax_login_object.loadingmessage;
		
		let formData = new FormData(form);
		
		fetch(
			ajax_login_object.ajaxurl,
			{ 
			    method: 'POST', 
			    body: formData,
			    credentials: 'same-origin'
// 			    headers: new Headers({'X-CSRF-TOKEN': sadmin.token, 'Content-Type': 'application/json'})
			}
		)
		.then(function(response) { return response.json(); })
		.then(function(data) {
			msg.innerHTML = data.message;
			if (data.success) {
				if (data.redirect) {
					document.location.href = data.redirect;
				}
				document.location.reload();
			}
		})
		.catch(function(error) {
			console.log('error',error);
		});
		
	}
	
}
login.init();

export { login as login }