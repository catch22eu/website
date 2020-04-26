<!-- HTML generated using hilite.me --><div style="background: #f8f8f8; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><pre style="margin: 0; line-height: 125%"><span style="color: #BC7A00">#include &lt;stdio.h&gt;</span>
<span style="color: #BC7A00">#include &lt;stdint.h&gt;</span>
<span style="color: #BC7A00">#include &lt;unistd.h&gt;</span>
<span style="color: #BC7A00">#include &lt;stdlib.h&gt;</span>
<span style="color: #BC7A00">#include &quot;prussdrv.h&quot;</span>
<span style="color: #BC7A00">#include &quot;pruss_intc_mapping.h&quot;</span>

<span style="color: #BC7A00">#define PRU_NUM 1			</span><span style="color: #408080; font-style: italic">// define which pru is used</span>
<span style="color: #BC7A00">#define SHM_OFFSET 2048		</span><span style="color: #408080; font-style: italic">// http://www.embedded-things.com/bbb/understanding-bbb-pru-shared-memory-access/</span>

<span style="color: #B00040">int</span> <span style="color: #0000FF">pru_init</span>(<span style="color: #B00040">void</span>)
{
	tpruss_intc_initdata pruss_intc_initdata <span style="color: #666666">=</span> PRUSS_INTC_INITDATA;
	prussdrv_init();
	<span style="color: #008000; font-weight: bold">if</span> (prussdrv_open(PRU_EVTOUT_0))
	{
		<span style="color: #008000; font-weight: bold">return</span> <span style="color: #666666">-1</span>;
	}
	<span style="color: #008000; font-weight: bold">else</span>
	{
		prussdrv_pruintc_init(<span style="color: #666666">&amp;</span>pruss_intc_initdata);
		<span style="color: #008000; font-weight: bold">return</span> <span style="color: #666666">0</span>;
	}
}

<span style="color: #B00040">void</span> <span style="color: #0000FF">pru_load</span>(<span style="color: #B00040">int</span> pru_num, <span style="color: #B00040">char</span><span style="color: #666666">*</span> datafile, <span style="color: #B00040">char</span><span style="color: #666666">*</span> codefile)
{
	<span style="color: #408080; font-style: italic">// load datafile in PRU memory</span>
	prussdrv_load_datafile(pru_num, datafile);
	<span style="color: #408080; font-style: italic">// load and execute codefile in PRU</span>
	prussdrv_exec_program(pru_num, codefile);
}

<span style="color: #B00040">void</span> <span style="color: #0000FF">pru_stop</span>(<span style="color: #B00040">int</span> pru_num)
{
	prussdrv_pru_disable(pru_num);
	prussdrv_exit();
}

<span style="color: #008000; font-weight: bold">volatile</span> <span style="color: #B00040">int32_t</span><span style="color: #666666">*</span> <span style="color: #0000FF">init_prumem</span>()
{
	<span style="color: #008000; font-weight: bold">volatile</span> <span style="color: #B00040">int32_t</span><span style="color: #666666">*</span> p;
	prussdrv_map_prumem(PRUSS0_SHARED_DATARAM, (<span style="color: #B00040">void</span><span style="color: #666666">**</span>)<span style="color: #666666">&amp;</span>p);
	<span style="color: #008000; font-weight: bold">return</span> p<span style="color: #666666">+</span>SHM_OFFSET;
}

<span style="color: #B00040">int</span> <span style="color: #0000FF">main</span>(<span style="color: #B00040">void</span>)
{
	<span style="color: #408080; font-style: italic">// initialize the PRU</span>
	printf(<span style="color: #BA2121">&quot;pruss driver init (%i)</span><span style="color: #BB6622; font-weight: bold">\n</span><span style="color: #BA2121">&quot;</span>, pru_init());

	<span style="color: #408080; font-style: italic">// load the PRU code (consisting of both code and data bin file).</span>
	pru_load(PRU_NUM, <span style="color: #BA2121">&quot;pru_data.bin&quot;</span>, <span style="color: #BA2121">&quot;pru_code.bin&quot;</span>);

	<span style="color: #408080; font-style: italic">// get the memory pointer to the shared data segment</span>
	<span style="color: #008000; font-weight: bold">volatile</span> <span style="color: #B00040">unsigned</span> <span style="color: #B00040">int</span><span style="color: #666666">*</span> pruDataMem <span style="color: #666666">=</span> init_prumem();

	printf(<span style="color: #BA2121">&quot;sending CAFEBABE to PRU</span><span style="color: #BB6622; font-weight: bold">\n</span><span style="color: #BA2121">&quot;</span>);

	<span style="color: #408080; font-style: italic">// write to shared data memory </span>
	pruDataMem[<span style="color: #666666">0</span>]<span style="color: #666666">=0xCAFEBABE</span>;

	sleep(<span style="color: #666666">1</span>);

	<span style="color: #408080; font-style: italic">// read from shared data memory</span>
	printf(<span style="color: #BA2121">&quot;PRU replies: %X</span><span style="color: #BB6622; font-weight: bold">\n</span><span style="color: #BA2121">&quot;</span>, pruDataMem[<span style="color: #666666">1</span>]);

	pru_stop(PRU_NUM);

	<span style="color: #008000; font-weight: bold">return</span> <span style="color: #666666">0</span>;
}
</pre></div>

