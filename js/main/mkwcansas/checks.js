var regcheck={
	wasinteraction:{
		nev:false,
		email:false,
		pw:false
	},
	nevcheck:function() {
		var vnev=$('#VezeteknevEdit'),
			msg=vnev.data('errormsg'),
			knev=$('#KeresztnevEdit'),
			nevmsg=$('#NevMsg');
		vnev[0].setCustomValidity('');
		knev[0].setCustomValidity('');
		nevmsg.empty();
		if (vnev[0].validity.valueMissing||knev[0].validity.valueMissing) {
			if (regcheck.wasinteraction.nev) {
				nevmsg.append(msg);
			}
			if (vnev[0].validity.valueMissing) {
				vnev[0].setCustomValidity(msg);
			}
			if (knev[0].validity.valueMissing) {
				knev[0].setCustomValidity(msg);
			}
		}
	},
	emailcheck:function() {
		var email=$('#EmailEdit'),
			msg1=email.data('errormsg1'),
			msg2=email.data('errormsg2'),
			emailmsg=$('#EmailMsg'),
			srvhiba=email.data('hiba')||{hibas:false};
		email[0].setCustomValidity('');
		emailmsg.empty();
		if (srvhiba.hibas) {
			emailmsg.append(srvhiba.uzenet);
			email[0].setCustomValidity(srvhiba.uzenet);
			email[0].checkValidity();
		}
		else {
			if (regcheck.wasinteraction.email) {
				email.removeClass('error').addClass('valid');
			}
			if (email[0].validity.valueMissing) {
				if (regcheck.wasinteraction.email) {
					emailmsg.append(msg1);
				}
				email[0].setCustomValidity(msg1);
			}
			else {
				if (email[0].validity.typeMismatch) {
					if (regcheck.wasinteraction.email) {
						emailmsg.append(msg2);
					}
					email[0].setCustomValidity(msg2);
				}
			}
		}
	},
	pwcheck:function() {
		var pw1=$('#Jelszo1Edit'),
			msg1=pw1.data('errormsg1'),
			msg2=pw1.data('errormsg2'),
			pw2=$('#Jelszo2Edit'),
			pwmsg=$('#JelszoMsg');
		pw1[0].setCustomValidity('');
		pw2[0].setCustomValidity('');
		pwmsg.empty();
		if (pw1.val()!==pw2.val()) {
			if (regcheck.wasinteraction.pw) {
				pwmsg.append(msg2);
			}
			pw2[0].setCustomValidity(msg2);
		}
		else {
			if (pw1[0].validity.valueMissing||pw2[0].validity.valueMissing) {
				if (regcheck.wasinteraction.pw) {
					pwmsg.append(msg1);
				}
				if (pw1[0].validity.valueMissing) {
					pw1[0].setCustomValidity(msg1);
				}
				if (pw2[0].validity.valueMissing) {
					pw2[0].setCustomValidity(msg1);
				}
			}
		}
	}
}

var kapcscheck={
	wasinteraction:{
		nev:false,
		email:false,
		tema:false
	},
	nevcheck:function() {
		var vnev=$('#NevEdit'),
			msg=vnev.data('errormsg'),
			nevmsg=$('#NevMsg');
		vnev[0].setCustomValidity('');
		nevmsg.empty();
		if (kapcscheck.wasinteraction.nev) {
			vnev.removeClass('error').addClass('valid');
		}
		if (vnev[0].validity.valueMissing) {
			if (kapcscheck.wasinteraction.nev) {
				nevmsg.append(msg);
			}
			if (vnev[0].validity.valueMissing) {
				vnev[0].setCustomValidity(msg);
			}
		}
	},
	emailcheck:function() {
		var email1=$('#Email1Edit'),
			email1msg=$('#Email1Msg'),
			msg1=email1.data('errormsg1'),
			msg2=email1.data('errormsg2'),
			msg3=email1.data('errormsg3'),
			email2=$('#Email2Edit'),
			email2msg=$('#Email2Msg'),
			srvhiba1=email1.data('hiba')||{hibas:false},
			srvhiba2=email2.data('hiba')||{hibas:false};
		email1[0].setCustomValidity('');
		email2[0].setCustomValidity('');
		email1msg.empty();
		email2msg.empty();
		if (srvhiba1.hibas||srvhiba2.hibas) {
			if (srvhiba1.hibas) {
				email1msg.append(srvhiba1.uzenet);
				email1[0].setCustomValidity(srvhiba1.uzenet);
				email1[0].checkValidity();
			}
			if (srvhiba2.hibas) {
				email2msg.append(srvhiba2.uzenet);
				email2[0].setCustomValidity(srvhiba2.uzenet);
				email2[0].checkValidity();
			}
		}
		else {
			if (kapcscheck.wasinteraction.email) {
				email1.removeClass('error').addClass('valid');
				email2.removeClass('error').addClass('valid');
			}
			if (email1.val()!=email2.val()) {
				if (kapcscheck.wasinteraction.email) {
					email2msg.append(msg3);
				}
				email1[0].setCustomValidity(msg3);
				email2[0].setCustomValidity(msg3);
			}
			else {
				if (email1[0].validity.valueMissing) {
					email1[0].setCustomValidity(msg1);
					if (kapcscheck.wasinteraction.email) {
						email1msg.append(msg1);
					}
				}
				else {
					if (email1[0].validity.typeMismatch) {
						email1[0].setCustomValidity(msg2);
						if (kapcscheck.wasinteraction.email) {
							email1msg.append(msg2);
						}
					}
				}
				if (email2[0].validity.valueMissing) {
					email2[0].setCustomValidity(msg1);
					if (kapcscheck.wasinteraction.email) {
						email2msg.append(msg1);
					}
				}
				else {
					if (email2[0].validity.typeMismatch) {
						email2[0].setCustomValidity(msg2);
						if (kapcscheck.wasinteraction.email) {
							email2msg.append(msg2);
						}
					}
				}
			}
		}
	},
	temacheck:function() {
		var tema=$('#TemaEdit'),
			msg=tema.data('errormsg'),
			temamsg=$('#TemaMsg');
		tema[0].setCustomValidity('');
		temamsg.empty();
		if (kapcscheck.wasinteraction.tema) {
			tema.removeClass('error').addClass('valid');
		}
		if (tema[0].validity.valueMissing) {
			tema[0].setCustomValidity(temamsg);
			if (kapcscheck.wasinteraction.tema) {
				temamsg.append(msg);
			}
		}
	}
};

var logincheck={
	wasinteraction:{
		email:false
	},
	emailcheck:function() {
		var email=$('#EmailEdit'),
			msg1=email.data('errormsg1'),
			msg2=email.data('errormsg2'),
			emailmsg=$('#EmailMsg'),
			srvhiba=email.data('hiba')||{hibas:false};
		email[0].setCustomValidity('');
		emailmsg.empty();
		if (srvhiba.hibas) {
			emailmsg.append(srvhiba.uzenet);
			email[0].setCustomValidity(srvhiba.uzenet);
			email[0].checkValidity();
		}
		else {
			if (logincheck.wasinteraction.email) {
				email.removeClass('error').addClass('valid');
			}
			if (email[0].validity.valueMissing) {
				if (logincheck.wasinteraction.email) {
					emailmsg.append(msg1);
				}
				email[0].setCustomValidity(msg1);
			}
			else {
				if (email[0].validity.typeMismatch) {
					if (logincheck.wasinteraction.email) {
						emailmsg.append(msg2);
					}
					email[0].setCustomValidity(msg2);
				}
			}
		}
	}
};
