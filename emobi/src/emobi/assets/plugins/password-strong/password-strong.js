class PasswordStrong 
{

	constructor (password, valid)
	{
		this.password = password;
		this.strong = 0;
		this.valid = valid;
	}
	
	getStrong ()
	{
		return this.strong;
	}

	matchPassword ()
	{
		var score = 0;
		if (this.password.match(/[a-z]+/))
		{
			score += 15;
		}
		if (this.password.match(/[A-Z]+/))
		{
			score += 15;
		}
		if (this.password.match(/[0-9]/))
		{
			score += 20;
		}
		if (this.password.match(/.*[-,!,@,#,$,%,^,&,*,.,?,_,~]/))
		{
			score += 20;
		}
		if (this.password.match(/[ \t]+$/)) 
		{
			score = 0;
		}
		return score;
	}
	
	countChars ()
	{
		var score = 0;
		var len = this.password.length;
		if (len >= 8 && len <=12)
		{
			score += 20;
		}
		
		if (len > 12)
		{
			score += 30;
		}
		
		return score;
	}
	
	score ()
	{
		this.strong = this.matchPassword() + this.countChars();
		return this.strong;
	}
	
	isValid ()
	{
		if (this.strong >= this.valid)
		{
			return true ;
		}
		else
		{
			return false ;
		}
			
	}
}