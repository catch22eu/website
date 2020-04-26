<!-- HTML generated using hilite.me --><div style="background: #f8f8f8; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><pre style="margin: 0; line-height: 125%"><span style="color: #BC7A00">#include &quot;pru_cfg.h&quot;</span>

<span style="color: #BC7A00">#define PRU_SHARED_MEM_ADDR 0x00012000</span>

<span style="color: #B00040">int</span> <span style="color: #0000FF">main</span>(<span style="color: #B00040">void</span>)
{
	<span style="color: #408080; font-style: italic">// direct pointer to memory address</span>
	<span style="color: #008000; font-weight: bold">volatile</span> <span style="color: #B00040">int</span><span style="color: #666666">*</span> shared_mem <span style="color: #666666">=</span> (<span style="color: #008000; font-weight: bold">volatile</span> <span style="color: #B00040">int</span> <span style="color: #666666">*</span>) PRU_SHARED_MEM_ADDR;

	<span style="color: #408080; font-style: italic">// enable OCP</span>
	CT_CFG.SYSCFG_bit.STANDBY_INIT <span style="color: #666666">=</span> <span style="color: #666666">0</span>;

	<span style="color: #008000; font-weight: bold">while</span>(shared_mem[<span style="color: #666666">0</span>]<span style="color: #666666">!=0xCAFEBABE</span>) {}

	shared_mem[<span style="color: #666666">1</span>]<span style="color: #666666">=0xDEADBEEF</span>;

	<span style="color: #008000; font-weight: bold">while</span>(<span style="color: #666666">1</span>) {}

	__halt();

	<span style="color: #008000; font-weight: bold">return</span> <span style="color: #666666">0</span>;
}
</pre></div>

