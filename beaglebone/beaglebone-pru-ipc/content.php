<div class="content">
  <h2>Welcome</h2>
  <p>Below is the code which shows how to read and write integers between the host ARM to and from shared memory inside the PRU. Thanks to Texane (Fabien le Mentec) and Owen who shared their code.</p>
  <p>Prerequisites are:</p>
  <ul>
  	<li>A Beaglebone of course</li>
  	<li>The uio driver working, (see <a href="http://catch22.eu/beaglebone/beaglebone-pru-uio/">this post</a>)</li>
  	<li>The c compiler from TI (which still can be found stand-alone on the TI website)</li>
  	<li>And some other files: AM335x_PRU.cmd (pick the right one, see remark below), bin.cmd</li>
  </ul>
  <p>For convenience, a <a href="https://github.com/catch22eu/pru_shm_c/tree/master">git repository</a> has been created to download all code at once. This also enables further improvements by the community. </p>
  </p>

  <h3>PRU C Code</h3>
  <p>The PRU code has now been updated with a much simpler code several times. Note the direct memory pointer, pointing to the first shared memory segment. See also <a href="http://catch22.eu/beaglebone/beaglebone-pru-c/">this</a> page describing some of the essentials of using c code for programming the PRU. </p>

  <?php include 'pru_code.php'; ?>  
  <br>
  <p>More brief explanation of what's going on in this code here could be at place. The PRU_SHARED_MEM_ADDR definition of 0x00012000 can be traced back to the pru reference manual, where it defines the start location of the shared memory segment. Next in the code, the volatile declaration of the shared_mem pointer is needed, as the memory content can be changed outside of what the code itself is doing. So in this case, the ARM host can update the memory, without the PRU knowing it. Depending on how compiler optimization is set (see the makefile below), it is possible the compiler optimizes the code to not poll the memory location over and over again, as it might think it useless to do when it did not change in the PRU code. The volatile keyword prevents these optimization errors. Next is the OCP enabling (see elsewhere), and further along in the code is the polling of the first memory location, and when it's condition is met, putting a value on the next memory location. </p>

  <h3>Host C Code</h3>
  <p>The host code makes use of the prussdrv or uio library to load the PRU code into memory, and also writes to values to shared memory, which is in return read by the PRU code. The code has also seen some changes in time, this is as of writing the latest version. Note the somewhat obscure 2048 offset, which is explained in the link in the code.</p>

  <?php include 'host_code.php'; ?>  
  <br>
	<p>The pru_init and pru_load are fairly straightforward implementations which can be found on the TI site explaining the prussdrv driver. Note that the latter is a generic implementation of a PRU code and data loader. Please also beware of some other implementations where prussdrv_exec_program is modified in case the PRU code is not started at the beginning of PRU memory. This has to do with the absence of a specific instruction in the AM335x_PRU.cmd file, so please check or use the provided command file in the git code, which ensures the _c_int00 (or start of the code) starts at location 0x0. The init_prumem is the function which returns a pointer to the shared memory segment with the somewhat obscure offset of 2048. See the reference in the code for an explanation of this. Further up in the code, after initializing the PRU via the pru_init() function, loading the PRU code, also here the volatile declaration of the pointer variable pointing to the shared memory segment. The rest of the code should explain itself. </p>

  <h3>Makefile</h3>
  <p>The Makefile is also given here. Pay attention here how to name all the files and ensure all's there. </p>

  <?php include 'build_code.php'; ?>  
  <br>

  <h3>Feedback?</h3>
  <p>Again, see the <a href="https://github.com/catch22eu/pru_shm_c/tree/master">git repository</a> to download all needed files and modify / branch if you have some improvements. Feel free to comment or give feedback on <a href="http://beagleboard.org/discuss?place=msg%2Fbeagleboard%2Fo6lBUisEAUA%2FAWh8sEugFQAJ">the beaglebone.org forum post</a> about this code. </p>


</div>
