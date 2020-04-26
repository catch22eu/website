<!-- HTML generated using hilite.me --><div style="background: #f8f8f8; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><pre style="margin: 0; line-height: 125%"><span style="color: #19177C">pru_options</span> 		<span style="color: #666666">=</span> --silicon_version<span style="color: #666666">=</span>2 --hardware_mac<span style="color: #666666">=</span>on -i/usr/include/arm-linux-gnueabihf/include -i/usr/include/arm-linux-gnueabihf/lib
<span style="color: #19177C">pru_compiler</span> 		<span style="color: #666666">=</span> /usr/bin/clpru
<span style="color: #19177C">pru_hex_converter</span> 	<span style="color: #666666">=</span> /usr/bin/hexpru

<span style="color: #0000FF">all</span><span style="color: #666666">:</span> <span style="color: #666666">exports host</span>

<span style="color: #0000FF">host</span><span style="color: #666666">:</span> <span style="color: #666666">pru_data.bin pru_code.bin host.c</span>
	gcc -std<span style="color: #666666">=</span>gnu11 host.c -o host -lprussdrv -lrt

<span style="color: #408080; font-style: italic">#</span>
<span style="color: #408080; font-style: italic"># Here&#39;s the PRU code generation part</span>
<span style="color: #408080; font-style: italic">#</span>

<span style="color: #0000FF">exports</span><span style="color: #666666">:</span>
	@export <span style="color: #19177C">PRU_SDK_DIR</span><span style="color: #666666">=</span>/usr
	@export <span style="color: #19177C">PRU_CGT_DIR</span><span style="color: #666666">=</span>/usr/include/arm-linux-gnueabihf

<span style="color: #0000FF">pru.obj</span><span style="color: #666666">:</span> <span style="color: #666666">pru.c</span>
	<span style="color: #008000; font-weight: bold">$(</span>pru_compiler<span style="color: #008000; font-weight: bold">)</span> <span style="color: #008000; font-weight: bold">$(</span>pru_options<span style="color: #008000; font-weight: bold">)</span> --opt_level<span style="color: #666666">=</span>off -c pru.c
	
<span style="color: #0000FF">pru.elf</span><span style="color: #666666">:</span> <span style="color: #666666">pru.obj </span>
	<span style="color: #008000; font-weight: bold">$(</span>pru_compiler<span style="color: #008000; font-weight: bold">)</span> <span style="color: #008000; font-weight: bold">$(</span>pru_options<span style="color: #008000; font-weight: bold">)</span> -z pru.obj -llibc.a -m pru.map -o pru.elf AM335x_PRU.cmd --quiet 

<span style="color: #0000FF">pru_code.bin pru_data.bin</span><span style="color: #666666">:</span> <span style="color: #666666">bin.cmd pru.elf</span>
	<span style="color: #008000; font-weight: bold">$(</span>pru_hex_converter<span style="color: #008000; font-weight: bold">)</span> bin.cmd ./pru.elf --quiet

<span style="color: #0000FF">clean</span><span style="color: #666666">:</span>
	rm pru.obj
	rm pru.elf
	rm pru.map
</pre></div>

