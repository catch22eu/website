<div class="content">
<h2>Introduction</h2>
<p>Programming and debugging ARM processors can be done using the Serial Wire Debug protocol, which is an extension to the JTAG protocoal. For this, there are several commercial JTAG adapters available, but there are more budget friendly alternatives available. Next to for example the <a href="http://catch22.eu/baremetal/lpc11xxgnu">FTDI adapter</a> described here, an even simpler solution is available, but just using the popular Raspberry Pi as SWD JTAG adapter. The big advantage using an SBC like this, is that there is no additional link between the adapter and host computer (usually via usb), but the mcu is programmed directly using the GPIO on the SBC itself. The Serial Wire Debug interface lends itself in simplicity, as it only uses 4 wires to connect to the arm processor:</p>
<ul>
  <li>GND: the ground connection</li>
  <li>nSRST: system reset</li>
  <li>SWDIO: the bi-directional data signal between the programmer/debugger and target mcu</li>
  <li>SWCLK: the SWD clock signal for the SWDIO signals</li> 
</ul>
<p>in this tutorial, the example is based on the Raspberry Pi Zero, connected to a STM32F051 via the swd interface:</p>
<img src="raspbery_pi_openocd.jpg" alt="Raspberry Pi as debug interface for STM32" height="100%" width="100%"><br><br>
<p>Note that VDD is supplied by the Raspberry Pi voltage regulator, and hence, both the Pi and the STM have the same IO voltage levels. Basis for this guide was <a href="https://petervanhoyweghen.wordpress.com/2015/10/11/burning-zero-bootloader-with-beaglebone-as-swd-programmer/">this explanation</a>, and shows sysfs is a generic means to use a Single Board Computer, so next to Raspberry Pi as the programming device, also a Beaglebone or any other linux SBC with GPIOs available through sysfs can be used. </p> 
<h2>About the Reset Signal</h2>
<p>The "n" in nSRST indicates reset is done by pulling the reset line "low" (meaning to ground). The STM32 MCU's have a weak pull-up resistor internally built-in, so when no connection is made, the mcu is not reset. Also, the SWD interface seems to be initiated only during reset. This can be examined by pulling the reset pin to ground permenently, and then connecting the SWD interface.</p>


<h3>About OpenOCD and Reset Configuration</h3>
<p>However, getting the reset configuration working can be challenging by the number of options and less concise documentation about this topic (see <a href="http://openocd.org/doc/html/Reset-Configuration.html">here</a>). The reset configuration is done by the option: </p>
<code>reset_config mode_flag</code>
<p>The mode_flag options can be specified in any order, but only one of each type. The relevant reset_config settings here are:</p>
<ul>
    <li>signals type: none (default), trst_only, srst_only and trst_and_srst. SRST refers to system reset (complete mcu reset), and TRST only resets the TAP controller of the mcu.</li>
    <li>gates type: srst_gates_jtag (default) indicates SRST prohibits JTAG. srst_nogate indicates JTAG commands are possible during SRST.</li>
    <li>srst type: srst_open_drain (default), and srst_push_pull. It sets the output type of the reset signal. </li>
    <li>connect type: connect_deassert_srst (default) indicates that SRST may not be active while connecting to the target. The opposite option is connect_assert_srst, which indicates that SRST must be active before any target connection is made. STM32 and STR9 use this. </li>
</ul>
<p>Given the explanation above, the working combination for sysfs and the STM32 sereies MCU's is:</p>
<code>
reset_config srst_only<br>
reset_config srst_nogate<br>
reset_config_srst_open_drain<br>
reset_config connect_assert_srst<br>
</code>
<p>Double check the actual parameters used by examining the openocd output. Tip: troubleshooting with an LED may be necessary, and be aware that SWD connection seems to be mcu and reset configuration dependant.</p>

<h2>Raspberry Pi as JTAG Adapter</h2>
<p>Now, there are two methods to use the GPIO's of the Raspberyy Pi. The first is to use sysfs, the generic means to to bitbanging, also useable on fir instance the Beaglebone. The second is using the BCM chip driver, a more native low-level and faster way. Both are tested and explained here. For both of them to be working, the correct version of openocd needs to be installed first. </p>

<h3>Installing OpenOCD</h3>
<p>OpenOCD needs to be compiled with the sysfs and/or BCM driver in order to use the Raspberry Pi as JTAG / SWD adapter. For Debian based releases (like Raspbian is), this is fortunately done starting with openocd v0.10. Unfortunately, at the time of writing of this tutorial, this version is available in the "buster" release of Debian, which is currently in "testing" phase and not yet the standard Raspbian release. See <a href="https://packages.debian.org/search?keywords=openocd&searchon=names&suite=all&section=all">this overview</a> of the available openocd versions and in which Debian releases they are available. There are several options from here on to optain the correctly compiled openocd, ranging from:</p>
<ul>
	<li>Running a mixed Debian system where an official Debian repository from a future release is added to the repository index (the often quoted as preferred option, see note 1 at the bottom of this page) </li>
	<li>Compiling yourself from source code (which takes quite some steps and if all done well about 30 muntes), see note 2) at the bottom of this page.</li>
	<li>Download and installing the official openocd version from a future release manually (which does not get auto-updated obviously, but comparable to compiling from source code although way faster and less error-prone)</li>
	<li>Running a recent-enough version of Raspbain (Again, see <a href="https://packages.debian.org/search?keywords=openocd&searchon=names&suite=all&section=all">this overview</a>).</li>
</ul>
<p>In the latter case, it simply means installing openocd the usual way (here via command-line):</p>
<code>sudo apt-get update<br>
sudo apt-get install openocd</code>


<h2>Using SysFS</h2>
<p>Some adapters were tested before (like the FTDI FT232H adapter), but especially the resistor hack used is a bit fiddly to work with and seems to be a hit-and-miss depending on your system used. In the end, this was not working reliably when using SWD (where in the case of the FT232H resistor hack, the in and out datalines are shorted through a resistor to make the SWDIO bi-directional connection via the standard JTAG in/out lines. A more elegant, and universal solution (at least using linux systems), is making use of the sysfs to control GPIO's of a board line Beaglebone or Raspberry Pi. The sysfs system is a way to access hardware and software through the filesystem by just reading and writing to/from files. The bi-directional data signal is managed by sysfs by alternating the GPIO between in -and output.</p>

<h3>The openocd.cfg file</h3>
A complete working configureation file is given here. Note the gpio pins used:
<code>
interface sysfsgpio<br>
<br>
# minimal swd setup<br>
sysfsgpio_swdio_num     24<br>
sysfsgpio_swclk_num     25<br>
sysfsgpio_srst_num      23<br>
<br>
transport select swd<br>
<br>
reset_config srst_only<br>
reset_config srst_nogate<br>
reset_config connect_assert_srst<br>
<br>
source [find target/stm32f0x.cfg]<br>
<br>
adapter_nsrst_delay 100<br>
adapter_nsrst_assert_width 100<br>
<br>
init<br>
targets<br>
reset halt<br>
</code>
<h3>Bitbanging Starts Here</h3>
<p>With the openocd.cfg file above, the STM32 can be connected to the Raspberry Pi:</p>
<img src="raspberry_as_programmer.jpg"alt="Raspberry Pi as debug interface for STM32" height="100%" width="100%"><br><br>
<p>Programming or debugging can start by simply starting openocd (as root), after ssh-ing into the raspberrry pi (for headless systems, meaning without a monitor and keyboard), or directly by opening a terminal on the Raspberry Pi:</p>
<code>
<span style="color: #00cc00">pi@raspberrypi</span>:<span style="color: #6666ff">~ $</span> sudo openocd<br>
Open On-Chip Debugger 0.10.0+dev-00197-g2168c475 (2017-10-07-18:12)<br>
Licensed under GNU GPL v2<br>
For bug reports, read<br>
	http://openocd.org/doc/doxygen/bugs.html<br>
SysfsGPIO num: swdio = 24<br>
SysfsGPIO num: swclk = 25<br>
SysfsGPIO num: srst = 23<br>
srst_only separate srst_gates_jtag srst_open_drain connect_deassert_srst<br>
srst_only separate srst_nogate srst_open_drain connect_deassert_srst<br>
srst_only separate srst_nogate srst_open_drain connect_assert_srst<br>
adapter speed: 1000 kHz<br>
adapter_nsrst_delay: 100<br>
srst_only separate srst_nogate srst_open_drain connect_assert_srst<br>
cortex_m reset_config sysresetreq<br>
adapter_nsrst_delay: 100<br>
adapter_nsrst_assert_width: 100<br>
Info : SysfsGPIO JTAG/SWD bitbang driver<br>
Info : SWD only mode enabled (specify tck, tms, tdi and tdo gpios to add JTAG mode)<br>
Info : This adapter doesn't support configurable speed<br>
Info : SWD DPIDR 0x0bb11477<br>
Info : stm32f0x.cpu: hardware has 4 breakpoints, 2 watchpoints<br>
&nbsp;&nbsp;&nbsp;&nbsp;TargetName&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Endian TapName&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;State<br>
--&nbsp;&nbsp;------------------ ---------- ------ ------------------ ------------<br>
&nbsp;0* stm32f0x.cpu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cortex_m&nbsp;&nbsp;&nbsp;little stm32f0x.cpu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;reset<br>
Error: Translation from khz to jtag_speed not implemented<br>
in procedure 'reset' called at file "openocd_sysfs.cfg", line 40<br>
in procedure 'ocd_bouncer' <br>
in procedure 'ocd_process_reset' <br>
in procedure 'ocd_process_reset_inner' called at file "embedded:startup.tcl", line 248<br>
in procedure 'stm32f0x.cpu' called at file "embedded:startup.tcl", line 286<br>
in procedure 'ocd_bouncer'<br>
<br>
target halted due to debug-request, current mode: Thread <br>
xPSR: 0xc1000000 pc: 0x080023a4 msp: 0x20001ffc<br>
</code>
<p>This shows that openocd correctly halted the STM32, and that there are 4 breakpoints and two watchpoints available. From here on, openocd can be used to program the mcu, or be debugged with dbg. See <a href="http://catch22.eu/baremetal/stm32f0xxgnu">this tutorial</a> how to do baremetal programming of the STM32 using gnu gcc tools. </p>
<h2>Using bcm2835gpio</h2>
<p>The other method of doing GPIO bit twiddling is using the more native bcm2835 driver. The advantage here is that the out-of-the-box openocd package from Raspbian can be used. In brief, just install openocd via:</p>
<code>apt-get install openocd</code>
<p>Then use following config file (at least, for the Raspberry Pi Zero W used here):</p>
<code>
source [find interface/raspberrypi-native.cfg]<br>
<br>
transport select swd<br>
<br>
source [find target/stm32f0x.cfg]<br>
bcm2835gpio_swd_nums 25 24<br>
bcm2835gpio_srst_num 23<br>
<br>
reset_config srst_only<br>
reset_config srst_nogate<br>
reset_config srst_open_drain<br>
reset_config connect_assert_srst <br>
<br>
adapter_nsrst_delay 100<br>
adapter_nsrst_assert_width 100<br>
<br>
init<br>
targets<br>
reset halt</code>
<p>And now, using openocd should show the following:</p>
<code>
<span style="color: #00cc00">pi@raspberrypi</span>:<span style="color: #6666ff">~ $</span> sudo openocd<br>
Open On-Chip Debugger 0.10.0+dev-00197-g2168c475 (2017-10-07-18:12)<br>
Licensed under GNU GPL v2<br>
For bug reports, read<br>
	http://openocd.org/doc/doxygen/bugs.html<br>
BCM2835 GPIO config: tck = 11, tms = 25, tdi = 10, tdo = 9<br>
adapter speed: 1000 kHz<br>
adapter_nsrst_delay: 100<br>
none separate<br>
cortex_m reset_config sysresetreq<br>
BCM2835 GPIO nums: swclk = 25, swdio = 24<br>
BCM2835 GPIO config: srst = 23<br>
srst_only separate srst_nogate srst_open_drain connect_deassert_srst<br>
srst_only separate srst_nogate srst_open_drain connect_deassert_srst<br>
srst_only separate srst_nogate srst_open_drain connect_deassert_srst<br>
srst_only separate srst_nogate srst_open_drain connect_assert_srst<br>
adapter_nsrst_delay: 100<br>
adapter_nsrst_assert_width: 100<br>
Info : BCM2835 GPIO JTAG/SWD bitbang driver<br>
Info : JTAG and SWD modes enabled<br>
Info : clock speed 1006 kHz<br>
Info : SWD DPIDR 0x0bb11477<br>
Info : stm32f0x.cpu: hardware has 4 breakpoints, 2 watchpoints<br>
&nbsp;&nbsp;&nbsp;&nbsp;TargetName&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Endian TapName&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;State<br>
--&nbsp;&nbsp;------------------ ---------- ------ ------------------ ------------<br>
&nbsp;0* stm32f0x.cpu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cortex_m&nbsp;&nbsp;&nbsp;little stm32f0x.cpu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;reset<br>
adapter speed: 1006 kHz<br>
target halted due to debug-request, current mode: Thread <br>
xPSR: 0xc1000000 pc: 0x080023a4 msp: 0x20001ffc</code>
<p>Note the final reset configuration in the output above: <span style="color: #cc0000">srst_only separate srst_nogate srst_open_drain connect_assert_srst</span>, which is crucial to get SWD working for the STM32. Happy coding. Again, see See <a href="http://catch22.eu/baremetal/stm32f0xxgnu">this guide</a> for a "Hello World" example. </p>

<h3>Notes</h3>
<h4>Note 1: Running a mixed Debian system</h4>
In brief, an additional Debian repository is added to the /etc/apt/sources.list file (the last line below):</p>
<code>deb http://mirrordirector.raspbian.org/raspbian/ <span style="color: #cc0000">stretch</span> main contrib non-free rpi<br>
# Uncomment line below then 'apt-get update' to enable 'apt-get source'<br>
#deb-src http://archive.raspbian.org/raspbian/ stretch main contrib non-free rpi<br>
<br>
deb http://mirrordirector.raspbian.org/raspbian/ <span style="color: #cc0000">buster</span> main contrib non-free rpi</code>
<p>Be aware of the versions shown in red above. Secondly, an additional file /etc/apt/apt.conf is added to inform the system what is the default release (this ensures that the system is kept per default on the currently installed release):
<code>APT::Default-Release "<span style="color: #cc0000">stretch</span>";</code>
Openocd from the added repository is then installed via the command:
<code>sudo apt-get -t <span style="color: #cc0000">buster</span> install openocd</code>

<h4>Note 2: Compiling OpenOCD</h4>
OpenOCD has sysfs support, but unfortunatley, is not compiled with this option per default (it's pending a request to add this wish list item at the Debian Package maintainers). The sequency of commands to download, compile and install openocd:</p>
<code>sudo apt-get update<br>
sudo apt-get install git autoconf libtool make pkg-config libusb-1.0-0 libusb-1.0-0-dev<br>
git clone git://git.code.sf.net/p/openocd/code openocd-code<br>
cd openocd-code<br>
./bootstrap<br>
./configure --enable-bcm2835gpio --enable-sysfsgpio<br>
make<br>
sudo make install</code>

</div>
