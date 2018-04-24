            <script type="text/javascript" src="<?=base_url();?>assets/admin/js/jquery.min.js"></script>
    		<!-- script type="text/javascript" src="<?=base_url();?>assets/admin/js/jquery.validate.min.js"></script> -->
            <script src="<?=base_url();?>assets/global/js/inputmask.js" type="text/javascript"></script>
            <script src="<?=base_url();?>assets/global/js/jquery.inputmask.js" type="text/javascript"></script>
            
            <script type="text/javascript">
                $(function() {
                        var scntDiv = $('#monday_input');
                        var i = $('#monday_input p').size() + 1;
                        $('#add_monday').on('click', function() {
                                //$('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" id="idInput" size="20" name="idInput[' + i +']" value="" placeholder="Input Nomor ' + i + '" /></span> <a href="#" id="HapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                $('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" class="time" id="mondayidInput[' + i +']" onkeyup="edit("idInput[' + i +']")" size="20" name="mondayidInput[' + i +']" value="" placeholder="hh:mm:ss" /> Sampai <input type="text" class="time" id="mondayidInput2[' + i +']" onkeyup="edit("idInput2[' + i +']")" size="20" name="mondayidInput2[' + i +']" value="" placeholder="hh:mm:ss" /></span> <a href="#" id="mondayHapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                i++;
                                $(".time").inputmask("99:99:99");
                                return false;
                        });
                       
                        $(document).on('click','#mondayHapusInput' ,function() {
                                if( i > 2 ) {
                                        $(this).parents('p').remove();
                                        i--;
                                }
                                return false;
                        });
                });
                
                $(function() {
                        var scntDiv = $('#tuesday_input');
                        var i = $('#tuesday_input p').size() + 1;
                        $('#add_tuesday').on('click', function() {
                                //$('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" id="idInput" size="20" name="idInput[' + i +']" value="" placeholder="Input Nomor ' + i + '" /></span> <a href="#" id="HapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                $('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" class="time" id="tuesdayidInput[' + i +']" onkeyup="edit("idInput[' + i +']")" size="20" name="tuesdayidInput[' + i +']" value="" placeholder="hh:mm:ss" /> Sampai <input type="text" class="time" id="tuesdayidInput2[' + i +']" onkeyup="edit("idInput2[' + i +']")" size="20" name="tuesdayidInput2[' + i +']" value="" placeholder="hh:mm:ss" /></span> <a href="#" id="tuesdayHapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                i++;
                                $(".time").inputmask("99:99:99");
                                return false;
                        });
                       
                        $(document).on('click','#tuesdayHapusInput' ,function() {
                                if( i > 2 ) {
                                        $(this).parents('p').remove();
                                        i--;
                                }
                                return false;
                        });
                });
                
                $(function() {
                        var scntDiv = $('#wednesday_input');
                        var i = $('#wednesday_input p').size() + 1;
                        $('#add_wednesday').on('click', function() {
                                //$('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" id="idInput" size="20" name="idInput[' + i +']" value="" placeholder="Input Nomor ' + i + '" /></span> <a href="#" id="HapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                $('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" class="time" id="wednesdayidInput[' + i +']" onkeyup="edit("idInput[' + i +']")" size="20" name="wednesdayidInput[' + i +']" value="" placeholder="hh:mm:ss" /> Sampai <input type="text" class="time" id="wednesdayidInput2[' + i +']" onkeyup="edit("idInput2[' + i +']")" size="20" name="wednesdayidInput2[' + i +']" value="" placeholder="hh:mm:ss" /></span> <a href="#" id="wednesdayHapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                i++;
                                $(".time").inputmask("99:99:99");
                                return false;
                        });
                       
                        $(document).on('click','#wednesdayHapusInput' ,function() {
                                if( i > 2 ) {
                                        $(this).parents('p').remove();
                                        i--;
                                }
                                return false;
                        });
                });
                
                $(function() {
                        var scntDiv = $('#Thursday_input');
                        var i = $('#Thursday_input p').size() + 1;
                        $('#add_Thursday').on('click', function() {
                                //$('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" id="idInput" size="20" name="idInput[' + i +']" value="" placeholder="Input Nomor ' + i + '" /></span> <a href="#" id="HapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                $('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" class="time" id="ThursdayidInput[' + i +']" onkeyup="edit("idInput[' + i +']")" size="20" name="ThursdayidInput[' + i +']" value="" placeholder="hh:mm:ss" /> Sampai <input type="text" class="time" id="ThursdayidInput2[' + i +']" onkeyup="edit("idInput2[' + i +']")" size="20" name="ThursdayidInput2[' + i +']" value="" placeholder="hh:mm:ss" /></span> <a href="#" id="ThursdayHapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                i++;
                                $(".time").inputmask("99:99:99");
                                return false;
                        });
                       
                        $(document).on('click','#ThursdayHapusInput' ,function() {
                                if( i > 2 ) {
                                        $(this).parents('p').remove();
                                        i--;
                                }
                                return false;
                        });
                });
                
                $(function() {
                        var scntDiv = $('#Friday_input');
                        var i = $('#Friday_input p').size() + 1;
                        $('#add_Friday').on('click', function() {
                                //$('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" id="idInput" size="20" name="idInput[' + i +']" value="" placeholder="Input Nomor ' + i + '" /></span> <a href="#" id="HapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                $('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" class="time" id="FridayidInput[' + i +']" onkeyup="edit("idInput[' + i +']")" size="20" name="FridayidInput[' + i +']" value="" placeholder="hh:mm:ss" /> Sampai <input type="text" class="time" id="FridayidInput2[' + i +']" onkeyup="edit("idInput2[' + i +']")" size="20" name="FridayidInput2[' + i +']" value="" placeholder="hh:mm:ss" /></span> <a href="#" id="FridayHapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                i++;
                                $(".time").inputmask("99:99:99");
                                return false;
                        });
                       
                        $(document).on('click','#FridayHapusInput' ,function() {
                                if( i > 2 ) {
                                        $(this).parents('p').remove();
                                        i--;
                                }
                                return false;
                        });
                });
                
                $(function() {
                        var scntDiv = $('#Saturday_input');
                        var i = $('#Saturday_input p').size() + 1;
                        $('#add_Saturday').on('click', function() {
                                //$('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" id="idInput" size="20" name="idInput[' + i +']" value="" placeholder="Input Nomor ' + i + '" /></span> <a href="#" id="HapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                $('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" class="time" id="SaturdayidInput[' + i +']" onkeyup="edit("idInput[' + i +']")" size="20" name="SaturdayidInput[' + i +']" value="" placeholder="hh:mm:ss" /> Sampai <input type="text" class="time" id="SaturdayidInput2[' + i +']" onkeyup="edit("idInput2[' + i +']")" size="20" name="SaturdayidInput2[' + i +']" value="" placeholder="hh:mm:ss" /></span> <a href="#" id="SaturdayHapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                i++;
                                $(".time").inputmask("99:99:99");
                                return false;
                        });
                       
                        $(document).on('click','#SaturdayHapusInput' ,function() {
                                if( i > 2 ) {
                                        $(this).parents('p').remove();
                                        i--;
                                }
                                return false;
                        });
                });
                
                $(function() {
                        var scntDiv = $('#Sunday_input');
                        var i = $('#Sunday_input p').size() + 1;
                        $('#add_Sunday').on('click', function() {
                                //$('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" id="idInput" size="20" name="idInput[' + i +']" value="" placeholder="Input Nomor ' + i + '" /></span> <a href="#" id="HapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                $('<p><span class="help-inline"><span for="kelompokinputLabel"><input type="text" class="time" id="SundayidInput[' + i +']" onkeyup="edit("idInput[' + i +']")" size="20" name="SundayidInput[' + i +']" value="" placeholder="hh:mm:ss" /> Sampai <input type="text" class="time" id="SundayidInput2[' + i +']" onkeyup="edit("idInput2[' + i +']")" size="20" name="SundayidInput2[' + i +']" value="" placeholder="hh:mm:ss" /></span> <a href="#" id="SundayHapusInput">Hapus</a></span></p>').appendTo(scntDiv);
                                i++;
                                $(".time").inputmask("99:99:99");
                                return false;
                        });
                       
                        $(document).on('click','#SundayHapusInput' ,function() {
                                if( i > 2 ) {
                                        $(this).parents('p').remove();
                                        i--;
                                }
                                return false;
                        });
                });
            </script>
            
            <script>
                /*$(document).ready(function(){
                   //$("#example1").inputmask("99:99:99");
                   $(".time").inputmask("99:99:99");
                });*/
                
                function edit(id)
                {
                    //$("#"+id).inputmask("99:99:99");
                    $(".time").inputmask("99:99:99");
                }
            </script>