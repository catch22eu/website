      <div class="content">
        <!-- CONTENT -->

        <h2>Design</h2>

        <h4>Simple Robot Design</h4>

        <p>Goal is to have the robot walking around without spending
        too much time on making it. To make choises easy, and prevent
        complications further along the building process, design
        constraints are defined. This has much impact on cost,
        complexity, and time to make the robot. Following constraints
        were defined for this robot:</p>

        <ol>
          <li>Simple robot design, using ordinary tools to make the
          robot</li>

          <li>Inexpensive and simple robot parts</li>

          <li>Much room for experimenting with algorithms</li>

          <li>Sensors for autonomous movement</li>

          <li>Wireless operation by using a standard robot servo
          controller</li>

          <li>Hexapod (6 legged) walking robot design (see <i>Figure
          1</i>)</li>
        </ol>

        <p><img src="side.jpg" alt="" border="0" height="338" width=
        "575" /><br />
        <i>Figure 1</i>: Simple robot design using inexpensive
        parts.</p>

        <h3>Robot Leg Design</h3>

        <h4>Hexapod Robot Design</h4>

        <p>So how does a simple 6 legged robot walk? After
        investigation, it was found that 6 legged robots like the
        Extreme Hexapod (<a href=
        "http://www.lynxmotion.com">http://www.lynxmotion.com</a>),
        with only 3 servos driving the robot, can move the robot not
        only forwards and backwards, but also can make the robot turn.
        The robot is also stable with always at least three three legs
        standing on the ground. Because of the simplicity of this
        hexapod design, this concept was chosen.</p>

        <h4>Improved Hexapod Design</h4>

        <p>After a careful study of a movie of the Extreme Hexapod
        robot walking in a straight line, it was found that the legs
        are slipping (rear legs the most), giving the impression of not
        being in control of the movement. After some further
        investigation, the cause of this is the rotation center of the
        front and rear legs not coinciding with pivot point of the
        opposite middle leg. The robot design described here does not
        have this shortcoming.<br />
        This robot complies to the rule of the pivot point (hence the
        name), see <i>figure 2</i> below. This is the first revision of
        the robot. Here you can see the robot standing on its right
        middle leg, and left front- and back leg. The right front- and
        back leg are lifted upwards, as well as the middle left leg.
        When the right servo is turning counterclockwise, robot will
        turn clockwise, and move forward. Because the right servo is
        located above the point where the middle leg is standing on the
        ground, the distance between the left middle leg and the front-
        and back right leg will remain the same. This is the first
        robot with this design concept.</p>

        <p><img src="pivotpoint.jpg" alt="" border="0" height="338"
        width="575" /><br />
        <i>Figure 2</i>: Rotation of the left legs while standing on
        the right middle leg, without sliding of legs.</p>

        <h4>Mechanics</h4>

        <p>To keep the design simple, servomotors are used both to
        drive the robot, and to act as hinges for the legs. This is
        because no hinges were found after a short search on the
        Internet for small hinges which could attached easily to the
        legs. (<a href=
        "http://www.graupner.com">http://www.graupner.com</a>)
        (<a href="http://www.servocity.com">http://www.servocity.com</a>)<br />

        For the robot&#39;s framework supporting the servos, batteries
        and the controller, standard aluminum strips found at hardware
        stores can be used. They are strong enough, do not corrode, are
        easy to process (cutting using a handsaw, drilling using a
        drill), and easy to attach to each other using nuts and bolts,
        or even maybe glue.<br />
        Best bet is using brass metal (<a href=
        "http://www.ksmetals.com">http://www.ksmetals.com</a>) found at
        hobby shops for the legs, and bending them in the desired
        shape, attach them directly to the servo wheels.</p>

        <h4>Robot Design Risks</h4>

        <p>All three servo motors are stressed extensively (see
        Calculations below):</p>

        <ol>
          <li>Motor torque for the middle legs is 50% of the stall
          torque, and maximum operating speed for the front and rear
          legs is not reached because of the mass moment of inertia of
          the robot.</li>

          <li>The servos are used as hinges for the legs, supporting
          the complete weight of the robot.</li>

          <li>Because it is not known how much the servos can stand
          before failure, and it is not known how long the robot will
          be in operation, the cheap servos are selected. In case the
          servos break down too soon, more durable servos can still be
          used.</li>

          <li>Unknown in the beginning was the way to manufacture the
          legs and attach them to the servos. Finally aluminum sheet
          was used.</li>
        </ol>

        <h3>Robot Components</h3>

        <h4>Robot Controller</h4><img style=
        "width: 242px; height: 242px;" alt="Acrononame Controller GP1"
        src="gp1.jpg" />

        <p>There are currently 3 controller brands: BasicX, OOPic, and
        Acroname. BasicX produces the BasicX-24 programmable micro
        controller, which is a 24-pin EEPROM with 32k memory to store
        code written in the Basic programming language. It has 21 I/O
        lines from which 8 can be used as 10bit analog inputs, 2 for
        serial communications only. The controller can be supplied with
        a development board, which has a serial connector to interface
        with a host.<br />
        OOPic stands for Object Oriented Programmable PIC. This
        controller is available on several controller boards, like the
        OOPic-R this board has an RS232 serial port, and 16 I/O lines
        for servos, I2C network, etc. Programs are written in Visual
        Basic, C++ and Java, and are compiled and downloaded to the
        controller via an freely downloadable Microsoft Windows
        Integrated Development Environment (IDE).<br />
        According to GMU Applied Robotics club, the first choice
        controller is however Acroname&#8217;s Brainstem controller. It
        has 4 servo-, 5 analog-, 5 digital inputs, an I2C bus and RS232
        Serial interface. It&#39;s size is 2.5 x 2.5&quot;. The servos
        connect directly to the controller pins, and are independant of
        the servo brand used. The RS232 interface requires a small,
        relatively cheap convertor board which has the standard 9 pin
        sub-d connector. Programs are written in Ansi C, and 11 1k
        programs can be stored, from which 4 can run concurrently. An
        interesting feature is the reflex architecture, which allows to
        respond based on sensor inputs. Programs are compiled and
        downloaded to the controller via Acroname&#8217;s Console
        program, which is free downloadable at the manufacturer&#39;s
        site.<br />
        Also mentioned here, is the &quot;attractive&quot; GumStick
        (<a href="http://www.gumstix.com">http://www.gumstix.com</a>)
        109$, Intel XScale PXA255 200MHz, 64MB SDRAM, 4 MB Strataflash,
        80 x 20 x 6.3mm. In a later stage of the project this embedded
        platform running Linux can be added to the robot to give much
        more room for experimenting with algorithms.</p>

        <h4>Robot Servo Motors</h4><img style=
        "width: 128px; height: 155px;" alt="Servo picture" src=
        "Hitec_HS-322HD.gif" />

        <p>RC servos can be categorized by their size, gear material
        and bearing types. Servo sizes are standardized, and are
        available in sub-micro, micro, mini, standard, and
        1/4 scale. For this
        robot, compared to the size of the Brainstem controller, and
        price, the standard size servo was chosen, which is 40 x 20 x
        36.5mm.<br />
        Wear and strength are determined by the gear type. There are
        four gear types used in servos. Nylon Gears are used most
        because of price, little or no wear, and weight. Karbonite
        gears are stronger (for instance, a nylon gear shaft bends
        easier than a karbonite one), and have better wear resistance.
        Metal gears are even stronger, but wear much faster. Top end
        servos are equipped with titanium gears, which are strongest,
        and have virtually no wear at all. Strength is important in
        this hexapod design, since the legs are directly attached to
        the servos.<br />
        Bearings used in servos are usually made of a plastic or metal
        shaft/bush combination. For heavy-duty applications, ball
        bearings are used. The servo used in this robot has plastic
        bearings, which still perform well, although a little play can
        be observed after 2 hours of operation. This can either be
        caused by the nylon gears (and shaft), or the nylon
        bearings.<br />
        RC Servos in general have an operating power range between 4.8
        and 6V, which is supplied by the red (+) and black or brown
        (ground) wires. The servo set point is given with a third wire,
        which is yellow, orange or white, depending on the servo brand.
        The signal is a pulse width modulated (PWM) signal with a
        frequency of 50Hz and amplitude of 3 - 5V. A pulse width of
        1.5ms will send the servo to its neutral position.<br />
        For the robot, the Hitec HS322HD has been chosen. It&#39;s
        slighty more expensive than the cheap 10$ servo and has
        karbonite gears. The robot performs well with these gears,
        although the stiffness of the legs could be improved by using
        stronger gear servos for this robot design. The servo weighs 43
        gram, draws a current of 500mA when loaded, and has a stall
        torque of 0.3Nm. See <a href=
        "http://www.hitecrcd.com/">www.hitecrcd.com</a> for more
        specifications, or the links page for different brand
        servos.</p>

        <h4>Power</h4>

        <p><i>Batteries: 4x2200mAh NimH AA, 10g, $3.5 each</i></p>

        <h3>Robot Calculations</h3>

        <p>After a selection is made which electronic components will
        probably be used in the robot design, the design is verified by
        calculating the total mass of the electronics, and an estimate
        is done for the total mass of the robot. The mass in turn will
        reveal if the chosen servo is capable of lifting the robot (the
        middle legs of this hexapod are connected to the servo which
        tilts the robot to the right or left side, see <span style=
        "font-style: italic;">Figure 2</span>).</p>

        <h4>Robot Weight</h4>

        <p>One of the most important aspects of the design is de mass
        of the robot. It determines the required force to lift the
        robot, and subsequenty the servos to be used. The speed is also
        dependant of the mass. In general for a hexapod, the heavier
        the robot is, the slower it will be, and shorter battery life
        will be. This robot is a relatively small robot and has three
        servo&#39;s, from which one servo is used to tilt the robot.
        More complex hexapod designs with more than three servo&#39;s
        for controlling the legs probably have shorter battery life
        since more than one servo have to lift the robot.<br />
        First, let&#39;s calculate the total mass of the electronic
        components:<br />
        &#160;&#160;M<sub>Electronics</sub> = M<sub>Brainstem</sub>+ 3
        x M<sub>servo</sub>+ 4 x M<sub>AA Batteries</sub> = 0.09 + 3 x
        0.043 + 4 x 0.010 = 0.178kg<br />
        Total mass of the robot is assumed to be twice as much as the
        mass of the electronics:<br />
        &#160;&#160;M<sub>Total</sub> = 2 x M<sub>Electronics</sub> = 2
        x 0.178kg = 0.356kg<br /></p>

        <h4>Servo Torque for Lifting the Robot</h4>

        <p>For this calculation, it is assumed the middle legs are a
        &quot;Brainstem&#39;s width&quot; apart. The middle leg, when
        doing nothing else than lifting the robot, has to lift half the
        weight of the robot. This has to be, since the robot is in
        equilibrium when it&#39;s not moving. This means there is no
        acceleration in any direction (both translation and rotation).
        There is no acceleration in vertical direction (say the robot
        is falling) because the gravitational force m x g (pointing
        downwards) is equal and oppisite to the total force exerted on
        the legs via the gound (pointing upwards). The same applies to
        rotation. Since the robot is not tumbling over in this
        equilibrium state, the total momentum of the forces on the
        centre of gravity of the robot is zero. So the momentum caused
        by the force exerted on the middle leg, is the same as the
        total momentum exerted on the opposite front and back leg. So
        this is the reason the middle leg lifts half the weight of the
        robot. Required torque to lift the robot for the middle leg
        would then be:<br />
        &#160; &#160;T = L<sub>Brainstem</sub> x &frac12; x 
        M<sub>total</sub> x g = 0.064 x &frac12; x 0.356 x 10 =
        0.14Nm<br />
        This is about half the stall torque of the servo. Conclusion:
        the chosen servo is capable of tilting the robot with the
        assumed mass of the robot.</p>

        <h4>Servo Torque for Walking</h4>

        <p>The other two servos are used to move the legs of the robot
        back and forward. According to the specifications of the
        intended servos, the operating speed at 4.8V is 60 / 0.19 =
        315&deg;/s. Assumed is
        that the legs will move +/- 15 degrees. The time to move a leg
        forwards or backwards would then be 0.05s. This is however not
        the actual speed, since the specifications are based on a servo
        without any load. Therefore, the mass of the robot has to be
        taken into account here.<br />
        Like Newton&#39;s second law F = m x a (a force F is required
        to accelerate a mass with acceleration a) for linear movements,
        the same is applicable for rotations. In this case, a momentum
        is required to give a body with a certain mass moment of
        inertia a specific angular acceleration. To calculate the mass
        moment of inertia, the weight is assumed to be distributed
        evenly with a radius of half a Brainstem&#39;s width plus half
        a servo width:<br />
        &#160; &#160;R = &frac12; x
        0.032 + &frac12; x 0.040 =
        36mm<br />
        Mass Moment of Inertia will then be:<br />
        &#160; &#160;I = R<sup>2</sup> x M<sub>Total</sub> =
        0.036<sup>2</sup> x 0.356 = 0.46 x
        10<sup>-3</sup>kgm<sup>2</sup><br />
        Acceleration is calculated with the stall torque of the
        servo:<br />
        &#160; &#160;a = T<sub>Stall</sub> / I = 0.3 / 0.46 x
        10<sup>-3</sup> = 650rad/s<sup>2</sup> =
        37&deg;/s<sup>2</sup><br />

        Time to reach operating speed:<br />
        &#160; &#160;T<sub>Operating</sub> = V<sub>Operating</sub> / a
        = 315 / 37 = 8s<br />
        This means the servo will not reach it&#39;s maximum operating
        speed.</p>

        <h4>Robot Power Consumption<br /></h4>

        <p>Total robot current consumption of one Brainstem and 3
        servos:<br />
        &#160; &#160;I<sub>Total</sub>= I<sub>Brainstem</sub>+ 3 x
        I<sub>Servo</sub> = 0.050 + 3 x 0.15 = 0.5A<br />
        Battery Life depends on the battery capacity Q of the 4 AA
        batteries, and the total robot current consumption:<br />
        &#160; &#160;T<sub>Battery</sub> = 4 x Q / I<sub>Total</sub> =
        4 x 0.2200 / 0.5 = 1.76h = 1:45h</p>

        <h4>Robot Building Costs</h4>

        <p>Acroname&#39;s controller, including serial interface is
        about 100$. The HS322HD servo and SRF04 ultrasonic range sensor
        cost about 15$ and 25$. Adding four chargeable batteries, and a
        battery pack totals to 190$. The other building materials (two
        sizes aluminum sheet, nuts and bolts), is about 20$. So the
        total cost of the robot is just above 200$, which is a cheap
        robot compared to other simple hexapod designs. However, take
        into account shipping costs and import / export fees. The
        shipment from the US to Holland resulted in an extra
        90$.</p><br />
        <br />
        
        <a href="http://catch22.eu/htcdesiresv/review/" >
        <img style="display: block; margin-left: auto; margin-right: auto;"
        src="http://catch22.eu/linkbanner2.jpg" alt="link" /></a>
        <br />
        
        <!-- END CONTENT -->
      </div>

