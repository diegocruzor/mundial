function Reloj()
{ 
	A = new Date(); 
	dateText = "";
	hora = A.getHours() 
	minuto = A.getMinutes() 
	segundo = A.getSeconds() 

	// Tomar el dia actual y convertirlo al espanol
	dayValue = A.getDay()
	if (dayValue == 0)
		dateText += "Domingo, "
	else if (dayValue == 1)
		dateText += "Lunes, "
	else if (dayValue == 2)
		dateText += "Martes, "
	else if (dayValue == 3)
		dateText += "Miercoles, "
	else if (dayValue == 4)
		dateText += "Jueves, "
	else if (dayValue == 5)
		dateText += "Viernes, "
	else if (dayValue == 6)
		dateText += "Sabado, "

	// tomar el mes actual y convertirlo a meses en espanol
	monthValue = A.getMonth()
	dateText += " "
	if (monthValue == 0)
		dateText += "Enero"
	if (monthValue == 1)
		dateText += "Febrero"
	if (monthValue == 2)
		dateText += "Marzo"
	if (monthValue == 3)
		dateText += "Abril"
	if (monthValue == 4)
		dateText += "Mayo"
	if (monthValue == 5)
		dateText += "Junio"
	if (monthValue == 6)
		dateText += "Julio"
	if (monthValue == 7)
		dateText += "Agosto"
	if (monthValue == 8)
		dateText += "Septiembre"
	if (monthValue == 9)
		dateText += "Octubre"
	if (monthValue == 10)
		dateText += "Noviembre"
	if (monthValue == 11)
		dateText += "Diciembre"
		
	// Para visualizar el ano, si es antes del 2000
	if (A.getYear() < 2000) 
		dateText += " " + A.getDate() + " de " + (1900 + A.getYear())
	else 
		dateText += " " + A.getDate() + " de " + (A.getYear())

	// Para visualizar la forma como se mira el tiempo
	if (segundo < 10) 
		segundo = "0" + segundo; 

	if (minuto < 10) 
		minuto = "0" + minuto; 
	
	if (hora < 10) 
		hora = "0" + hora; 

	if (hora < 12)
	{
		timeText = hora + ":" + minuto + ":" + segundo + " - "//+ " a.m. "
	}
	else if(hora >= 12)
	{
		timeText = hora + ":" + minuto + ":" + segundo  + " - "//+ " p.m. "
	}
	horaImprimible = timeText + dateText
	
	document.form_reloj.reloj.value = horaImprimible 

	setTimeout("Reloj()",1000) 

}
