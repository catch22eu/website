<div class="content">
<h2>Introduction</h2>
<p>This web page guides in programming the NXP LPC1100 ARM microprocessor series. The aim is to provide a compelling, easy to use method using an open source toolchain setup next to the commercially available products like Keil, mbed, LPCXpresso, etc.
As basis for this tutorial is a Debian based system, which also includes the popular Ubuntu Distribution. By making use of the available open source packages in this distribution, a generic way of  setting up a toolchain for other distributions should be straightforward as well. As an example, the LPC1114 MCU is used to show how to blink a LED. This MCU is one of 32-bit ARM Cortex MO processors available in DIP format, which makes it easy to use for prototyping.</p>

<h2>Setting up the GNU Toolchain</h2>
<?php include '../stm32f0xxgnu/gcc-arm-none-eabi.php'; ?>
<p>Next to this, in order to flash your binary to the MCU, a program called lpc21isp will be used:</p>
<code>sudo apt-get install lpc21isp</code>

<h2>Acquire the LPC libraries</h2>
<p>The example used here, makes use of the open source libraries provided by NXP for the selected MCU.    These can be downloaded from the NXP site directly, or by downloading the same library via the created <a href="https://github.com/catch22eu/LPC11xx-LPCXpresso-CMSIS">git repository</a>. The latter also requires the git package to be installed:</p>
<code>sudo apt-get install git</code>
<p>after which the LPC library can be cloned:</p>
<code>git clone https://github.com/catch22eu/LPC11xx-LPCXpresso-CMSIS</code>
<p>Next to that, the <a href="https://github.com/catch22eu/LPC1114_BlinkLED">source code</a> of the example below can now also be installed (without changing directory) from the previous command:</p>
<code>git clone https://github.com/catch22eu/LPC1114_BlinkLED</code>
<p>Note that this also provides the needed linker script and makefile for using the LPC library with the GNU compiler.</p>
 
<h2>Setting up the programmer and LPC1114</h2>
<p>In this case, the Adafruit FT232H Breakout board is used to program the LPC1114. The FT232H is used in this case as a USB to UART modem. Likely, other UART’s can be used as well. The LPC MCU has a bootloader which check upon reset (when Reset pin 23 is pulled low), if pin 24 is pulled low as well. In that case, pin 15/16 can be connected to a UART device (with RX and TX lines) to upload the binary, see figure below. The lpc21isp program can be instructed to use the additional DTR and RTS UART signals to control the reset – and bootloader pin. Next to that, the LPC needs to be powered by a 3.3V power source and grounded with the UART device. Here a breadboard is used to achieve all needed connections. </p>
<img src="lpc1114protoboard.png" alt="LPC1114 baremetal programming setup" height="100%" width="100%">
<p>Also, as explained <a href="">here</a>, the udev rules can be adapted to make the usb device read/writeable by a normal user instead of root (otherwise the commands related to the FT232H board need to be performed with sudo</p><br>

<h2>Hello World Example</h2>
<br>
<h3>main.c</h3>
<p>The first example here is using the mentioned LPC libraries, but not making use of the hardware abstraction layers for the sake of simplicity. The main.c file code is just less than 20 lines to blink an LED:</p>
<!-- HTML generated using hilite.me --><div style="background: #f8f8f8; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><pre style="margin: 0; line-height: 125%"><span style="color: #BC7A00">#include &quot;LPC11xx.h&quot;</span>

<span style="color: #BC7A00">#define GPIO1DATA		(*((volatile uint32_t *) 0x50013FFC ))</span>
<span style="color: #BC7A00">#define GPIO1DIR		(*((volatile uint32_t *) 0x50018000 ))</span>

<span style="color: #BC7A00">#define SYSAHBCLKCTRL	(*((volatile uint32_t *) 0x40048080 ))</span>

<span style="color: #B00040">int</span> <span style="color: #0000FF">main</span>(<span style="color: #B00040">void</span>)
{
	<span style="color: #408080; font-style: italic">// set pin 5 of GPIO1 as output </span>
	GPIO1DIR <span style="color: #666666">|=</span> (<span style="color: #666666">1</span> <span style="color: #666666">&lt;&lt;</span> <span style="color: #666666">5</span>);

	<span style="color: #008000; font-weight: bold">while</span>(<span style="color: #666666">1</span>)
	{
		<span style="color: #408080; font-style: italic">// toggle bit 5 of GPIO1</span>
		GPIO1DATA <span style="color: #666666">^=</span> (<span style="color: #666666">1</span> <span style="color: #666666">&lt;&lt;</span> <span style="color: #666666">5</span>);
		<span style="color: #408080; font-style: italic">//wait loop</span>
		<span style="color: #008000; font-weight: bold">for</span> (<span style="color: #B00040">int</span> i<span style="color: #666666">=0</span>; i<span style="color: #666666">&lt;1000000</span>;i<span style="color: #666666">++</span>); 
	}
}
</pre></div>
<br>
<h3>Makefile</h3>
<p>The Makefile is way more complicated, see <a href="https://github.com/catch22eu/LPC1114_BlinkLED/blob/master/Makefile">this link</a> to show the complete Makefile. In essence, the previous main.c file is being compiled by the Makefile with the following one big command:</p>
<code>arm-none-eabi-gcc -c -Wall -Os -std=c99 -specs=nosys.specs -DUSE_OLD_STYLE_DATA_BSS_INIT -mcpu=cortex-m0 -mthumb -Wl,-Map,output.map -I../LPC11xx-LPCXpresso-CMSIS/CMSISv2p00_LPC11xx/inc/ -DCORE_M0 -DENABLE_UNTESTED_CODE main.c -o main.o</code>
<p>This shows the use of the gcc compiler to take main.c as input, and produce main.o as output using the c99 standard. ARM specific settings are -mcpu-cortex-m0 and and -mthumb, which indicate the type of ARM core is cimpiled for and the mthumb instruction set. The LPCXpresso library path is defined with the unclude command -I. Last but not least are some defines (-D...) which need to be set to indicate specific preprocessor directives: USE_OLD_STYLE_DATA_BSS_INIT which is related to the linker script style, and CORE_M0 and ENABLE_UNTESTED_CODE. <p>
<p>Next to the main.c file, also the two files in the LPC library directores are compiled: LPC11xx-LPCXpresso-CMSIS/CMSISv2p00_LPC11xx/src/core_cm0.c, and LPC11xx-LPCXpresso-CMSIS/CMSISv2p00_LPC11xx/src/system_LPC11xx.c.<p>
<p>In a separate step, the created object files are linked together with using the <a href="https://github.com/catch22eu/LPC1114_BlinkLED/blob/master/lpc1114.ld">linker script lpc1114.ld</a>. This linker script is also tightly related to the LPC library. In brief, the memory of the LPC1114 is defined as:</p>
<code>
MEMORY<br>
{<br>
&nbsp;&nbsp;&nbsp;&nbsp;flash (rx)  : org = 0x00000000, len = 32k<br>
&nbsp;&nbsp;&nbsp;&nbsp;ram   (xrw) : org = 0x10000000, len = 4k<br>
}</code>
<p>The next important part is the reference to two constructs:
<code>
KEEP (*(.isr_vector));<br>
KEEP (*(.after_vectors));
</code>
<p>What this actually does is ensure the vector table defined in the file <a href="https://github.com/catch22eu/LPC11xx-LPCXpresso-CMSIS/blob/master/Blinky/src/cr_startup_lpc11.c">cr_startup_lpc11.c</a> is located at the beginning of flash memory (it contains the beginning of the stack pointer _vStackTop (the top of the RAM), and the Program Counter (where in flash the first insruction is located)), and that the startup instructions are located after that. This can be seen in this part of the startup code where tha same .isr_vecor is referenced, as well as _vStackTop:</p>

<!-- HTML generated using hilite.me --><div style="background: #f8f8f8; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em;"><pre style="margin: 0; line-height: 125%"><span style="color: #408080; font-style: italic">//*****************************************************************************</span>
<span style="color: #408080; font-style: italic">//</span>
<span style="color: #408080; font-style: italic">// The vector table.  Note that the proper constructs must be placed on this to</span>
<span style="color: #408080; font-style: italic">// ensure that it ends up at physical address 0x0000.0000.</span>
<span style="color: #408080; font-style: italic">//</span>
<span style="color: #408080; font-style: italic">//*****************************************************************************</span>
<span style="color: #008000; font-weight: bold">extern</span> <span style="color: #0000FF">void</span> (<span style="color: #666666">*</span> <span style="color: #008000; font-weight: bold">const</span> g_pfnVectors[])(<span style="color: #B00040">void</span>);
__attribute__ ((section(<span style="color: #BA2121">&quot;.isr_vector&quot;</span>)))
<span style="color: #B00040">void</span> (<span style="color: #666666">*</span> <span style="color: #008000; font-weight: bold">const</span> g_pfnVectors[])(<span style="color: #B00040">void</span>) <span style="color: #666666">=</span> {
    <span style="color: #666666">&amp;</span>_vStackTop,		    				<span style="color: #408080; font-style: italic">// The initial stack pointer</span>
    ResetISR,                               <span style="color: #408080; font-style: italic">// The reset handler</span>
    NMI_Handler,                            <span style="color: #408080; font-style: italic">// The NMI handler</span>
    HardFault_Handler,                      <span style="color: #408080; font-style: italic">// The hard fault handler</span>
    <span style="color: #666666">0</span>,                      				<span style="color: #408080; font-style: italic">// Reserved</span>
    <span style="color: #666666">0</span>,                      				<span style="color: #408080; font-style: italic">// Reserved</span>
<span style="color: #666666">0</span>, <span style="color: #408080; font-style: italic">// Reserved</span>
</pre></div>

<br>
<p>Also, the pointer ResetISR is used here, which ensures the correct pointer to the startup code. Some other references which are beginning with an underscore like _etext are also referenced in the cr_startup_lpc11.c file. It is recommended to check this file especially as it's the core of the startup of the MCU. The routines defined in the ResetISR function in the startup code also start the SystemInit() function (but this needs to be switched on by explicitly defining __USE_CMSIS), and finally hand over execution to the main.c code.</p>
<p>The last thing the Makefile does is copy the .elf file into a .bin file and flash the image after successfull compilation with the command:</p>
<code>lpc21isp -control -bin main.bin /dev/ttyUSB0 115200 14746</code>
<p>Note here that the checksum, which is used in LPC devices, is also calculated and flashed by lpc21isp utility. Happy coding!</p>



</div>
