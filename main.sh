

vkey="qOMnMJiIlGV40AgHkLxaLiZGtTQ1vSE2wuqbo0wo";
vdays=30;
vIPs="/home/serj/abuses/wp_ranges";
#vIPs="/home/serj/abuses/r";
semail="sagaragrey@gmail.com";


fJSON="/tmp/jsattach.txt";
rm -f $fJSON;

for i in $(cat $vIPs); 
	do 
		vJSON=`curl   "https://www.abuseipdb.com/check-block/json?key=$vkey&network=$i&days=$vdays";`
		echo $vJSON >> $fJSON;
	done		
echo -----------------
rm -f /tmp/wpattach.txt;

echo $(date) > /tmp/wpattach.txt;
cat $fJSON | strings | jq -r '.reportedIPs' >> /tmp/wpattach.txt;

unix2dos /tmp/wpattach.txt;

swaks --from abuseipdb@protoroot.com --header "Subject: WP hakers :D " --body "Report for the last $vdays days"   --attach-type text/html --attach /tmp/wpattach.txt --server localhost  --to $semail	
	
