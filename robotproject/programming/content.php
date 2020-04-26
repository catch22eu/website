      <div class="content">
        <!-- CONTENT -->

        <h3>Controller Programming</h3>

        <h4>Using the Brainstem GP1.0 controller</h4>

        <p>This section explains the usage of the Acroname Brainstem
        GP1.0: how programs are written, compiled, and loaded into the
        robot controller.</p>

        <h4>Writing TEA programs</h4>

        <p>To start of, the programs which run in Acroname&#39;s
        Brainstem controller are written in the &quot;TEA&quot;
        language which syntax is very similar to the C programming
        language. Any text editor can be used to edit the code. The
        code is saved as a &quot;.tea&quot; file, but needs to be
        compiled in order for the brainstem controller to understand
        the code.</p><img style="width: 240px; height: 240px;" alt=
        "Console example to compile a TEA program" src=
        "console.gif" /><br />

        <p><i>Figure 1</i>: Compiling a TEA program using the
        console.</p>

        <h4>Compiling TEA programs to CUP code</h4>

        <p>Compilation is done via Acroname&#39;s console, which is
        downloadable at the companies website. The console is available
        for Win32, MacOS X, and Linux. After downloading and installing
        this software, the console can be found in the aBinary
        directory on your computer as console.exe.<br />
        The TEA code which is used to control the robot has to be saved
        in the aUser directory. The code which is written for Pivot is
        saved as r01.tea in this directory. Compiling is done by
        starting the console, and giving the command steep
        &quot;r01.tea&quot;, see <span style=
        "font-style: italic;">Figure 1</span>. After succesfull
        compilation, this creates a r01.cup file in the aObjects
        directory.</p>

        <h4>Downloading CUP code to the Brainstem controller</h4>

        <p>Once the TEA program is compiled into a .cup file, the
        program can be downloaded via the RS232 interface of the
        Brainstem. This is done by connecting the Brainstem controller
        via the serial interface convertor and RS232 cable to the host
        computer. With the robot powered up with batteries, the console
        indicates via a green blinking status indicator that the
        Brainstem is connected to your computer and console program.
        After the command load &quot;r01.cup&quot; 2 0 is given to
        download the code to the Brainstem, the command launch 2 0
        starts executed the program on the controller. Note that the 2
        stands for the module number, which defaults to 2 for the
        Brainstem controller. In case several Brainstem modules are
        connected, the next Brainstam module has number 4. The 0 stands
        for the slot number in which the code is loaded, which can be
        any slot ranging from 0 to 10.</p>

        <h3>Robot Programming</h3>

        <p>Below is the basic TEA code for the robot to make some
        steps, and to let it turn. The program does not have the
        capability to use the SRF04 range finder yet, and consequently
        also no obstacle avoidance capability. The basic code is used
        to experiment with the walking routines and to calibrate the
        robot. To give an indication of the size of the program, it
        compiles into 333 bytes.<br />
        The code is devided into three separate parts:<br /></p>

        <ul>
          <li>r01.tea: This file contains the main program loop where
          the commands are given to move the robot around. The file
          also contains the include code for generic tea code
          (ACore.tea, aGP.tea, AServo.tea and aPrint.tea), which is
          supplied by Acroname, and the two custom parts which has to
          be written by the user.</li>

          <li>r01_dat.tea: This file can be considered the
          configuration file for the robot. Several parts can be
          identified in following order: definition of which servo is
          connected to which digital I/O, definition of the servo
          speeds, and definition of the three leg positions. The last
          definition can be considered as the calibration data of the
          legs.</li>

          <li>r01_servocmds.tea: In this file the servo commands to
          control the servos are defined. There are 6 commands defined.
          The first two procedures switch the servos on and off. The
          third procedure is the actual command to set the three servos
          of the robot to a specific position. The next three commands
          call this procedure several times to have the robot perform
          one step.</li>
        </ul>

        <p>r01.tea:</p>

        <p><img style="width: 599px; height: 532px;" alt=
        "Main robot controller program loop" src="r01.tea.gif" /></p>

        <p>r01_dat.tea:</p>

        <p><img style="width: 599px; height: 532px;" alt=
        "Robot controller servo calibration" src=
        "r01_dat.tea.gif" /></p>

        <p>r01_servocmds.tea:</p>

        <p><img style="width: 599px; height: 982px;" alt=
        "Robot controller servo commands" src=
        "r01_servocmds.tea.gif" /></p><br />

        <a href="http://catch22.eu/htcdesiresv/review/" >
        <img style="display: block; margin-left: auto; margin-right: auto;"
        src="http://catch22.eu/linkbanner2.jpg" alt="link" /></a>
        <br />
        
        <!-- END CONTENT -->
      </div>

