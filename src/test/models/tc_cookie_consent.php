<?php
/**
 *
 * @fixture cookie_consent_categories
 */
class TcCookieConsent extends TcBase {

	function test(){
		$request = new HTTPRequest();
		$settings = CookieConsent::GetSettings($request);

		$this->assertTrue($settings->accepted("necessary"));
		$this->assertFalse($settings->accepted("analytics"));
		$this->assertFalse($settings->accepted("advertising"));
		$this->assertFalse($settings->acceptedAll());
		$this->assertFalse($settings->rejectedAll());
		$this->assertTrue($settings->needsToBeConfirmed());

		$settings->acceptAll();
		$this->assertTrue($settings->accepted("necessary"));
		$this->assertTrue($settings->accepted("analytics"));
		$this->assertTrue($settings->accepted("advertising"));
		$this->assertTrue($settings->acceptedAll());
		$this->assertFalse($settings->rejectedAll());
		$this->assertFalse($settings->needsToBeConfirmed());

		$settings->rejectAll();
		$this->assertTrue($settings->accepted("necessary"));
		$this->assertFalse($settings->accepted("analytics"));
		$this->assertFalse($settings->accepted("advertising"));
		$this->assertFalse($settings->acceptedAll());
		$this->assertTrue($settings->rejectedAll());
		$this->assertFalse($settings->needsToBeConfirmed());
	}

	function test_compileCookieData(){
		$request = new HTTPRequest();
		$settings = CookieConsent::GetSettings($request,["current_time" => 1637443696]);
		$time = "1637443696";

		$data = $settings->compileCookieData();
		$this->assertEquals("",$data["all_a"]); // all aceppted?
		$this->assertEquals("",$data["all_t"]); // timestamp
		$this->assertEquals(CookieConsent::VERSION,$data["c_v"]);
		$this->assertEquals($time,$data["c_t"]);

		// categories
		$this->assertEquals([
			"necessary" => ["a" => "", "t" => "", "v" => ""],
			"analytics" => ["a" => "", "t" => "", "v" => ""],
			"advertising" => ["a" => "", "t" => "", "v" => ""],
		],$data["cs"]);

		//
		$settings->accept("necessary");
		$settings->accept("analytics");
		$settings->reject("advertising");

		$data = $settings->compileCookieData();
		$this->assertEquals("",$data["all_a"]); // all aceppted?
		$this->assertEquals("",$data["all_t"]); // timestamp

		$this->assertEquals([
			"necessary" => ["a" => "a", "t" => $time, "v" => "1"],
			"analytics" => ["a" => "a", "t" => $time, "v" => "2"],
			"advertising" => ["a" => "r", "t" => $time, "v" => "3"],
		],$data["cs"]);

		//
		$settings->acceptAll();

		$data = $settings->compileCookieData();
		$this->assertEquals("a",$data["all_a"]); // all aceppted?
		$this->assertEquals($time,$data["all_t"]); // timestamp

		$this->assertEquals([
			"necessary" => ["a" => "a", "t" => $time, "v" => "1"],
			"analytics" => ["a" => "a", "t" => $time, "v" => "2"],
			"advertising" => ["a" => "a", "t" => $time, "v" => "3"],
		],$data["cs"]);

		//
		$settings->rejectAll();

		$data = $settings->compileCookieData();
		$this->assertEquals("r",$data["all_a"]); // all aceppted?
		$this->assertEquals($time,$data["all_t"]); // timestamp

		$this->assertEquals([
			"necessary" => ["a" => "a", "t" => $time, "v" => "1"],
			"analytics" => ["a" => "r", "t" => $time, "v" => "2"],
			"advertising" => ["a" => "r", "t" => $time, "v" => "3"],
		],$data["cs"]);
	}

	function test__cleanCookieValues(){
		$request = new HTTPRequest();
		$settings = CookieConsent::GetSettings($request,["current_time" => 1637443696]);

		$data = ["c_v" => "", "c_t" => ""];
		$clean = $settings->_cleanCookieValues($data,["c_v","c_t"],true);
		$this->assertEquals(null,$clean);

		$data = ["c_v" => "1.1", "c_t" => "1637443600"];
		$clean = $settings->_cleanCookieValues($data,["c_v","c_t"],true);
		$this->assertEquals(["1.1","1637443600"],$clean);

		// both are empty
		$data = ["c_v" => "", "c_t" => ""];
		$clean = $settings->_cleanCookieValues($data,["c_v","c_t"],false);
		$this->assertEquals(["",""],$clean);

		// both are not empty
		$data = ["c_v" => "1.1", "c_t" => "1637443600"];
		$clean = $settings->_cleanCookieValues($data,["c_v","c_t"],false);
		$this->assertEquals(["1.1","1637443600"],$clean);

		// both are not set not empty
		$data = ["c_v" => "1.1", "c_t" => ""];
		$clean = $settings->_cleanCookieValues($data,["c_v","c_t"],false);
		$this->assertEquals(null,$clean);

		// one is missing
		$data = ["c_v" => ""];
		$clean = $settings->_cleanCookieValues($data,["c_v","c_t"],false);
		$this->assertEquals(null,$clean);

		// Timestamps
		// ==========

		// ok
		$data = ["t" => "1637443600"];
		$clean = $settings->_cleanCookieValues($data,["t"]);
		$this->assertEquals([1637443600],$clean);

		// time in a near future - ok
		$time_in_future = 1637443696 + 60 * 60;
		$data = ["t" => "$time_in_future"];
		$clean = $settings->_cleanCookieValues($data,["t"]);
		$this->assertEquals([$time_in_future],$clean);
		$this->assertTrue(is_int($clean[0]));

		// time in a far future - not ok
		$time_in_future = 1637443696 + 60 * 60 * 48;
		$data = ["t" => "$time_in_future"];
		$clean = $settings->_cleanCookieValues($data,["t"]);
		$this->assertEquals(null,$clean);

		// time in past - ok
		$time_in_past = 1637443696 - 60 * 60 * 24 * 365 * 2;
		$data = ["t" => $time_in_past];
		$clean = $settings->_cleanCookieValues($data,["t"]);
		$this->assertEquals([$time_in_past],$clean);

		// time in far past - not ok
		$time_in_past = 1637443696 - 60 * 60 * 24 * 365 * 20;
		$data = ["t" => $time_in_past];
		$clean = $settings->_cleanCookieValues($data,["t"]);
		$this->assertEquals(null,$clean);

		// invalid format
		$data = ["t" => "163744x696"];
		$clean = $settings->_cleanCookieValues($data,["t"]);
		$this->assertEquals(null,$clean);

		// CookieConsent version
		// =====================

		$data = ["c_v" => "0.10.20"];
		$clean = $settings->_cleanCookieValues($data,["c_v"]);
		$this->assertEquals(["0.10.20"],$clean);

		$data = ["c_v" => "0.0"];
		$clean = $settings->_cleanCookieValues($data,["c_v"]);
		$this->assertEquals(["0.0"],$clean);

		$this->assertNull($settings->_cleanCookieValues(["c_v" => "0.0.1.2"],["c_v"]),"too manu dots");
		$this->assertNull($settings->_cleanCookieValues(["c_v" => "01.1"],["c_v"]),"invalid version number");
		$this->assertNull($settings->_cleanCookieValues(["c_v" => "0.01"],["c_v"]),"invalid version number");
		$this->assertNull($settings->_cleanCookieValues(["c_v" => "xxx"],["c_v"]),"invalid version");

		// Acceptance flag
		// ===============

		$this->assertEquals([true],$settings->_cleanCookieValues(["a" => "a"],["a"]));
		$this->assertEquals([true],$settings->_cleanCookieValues(["all_a" => "a"],["all_a"]));
		$this->assertEquals([false],$settings->_cleanCookieValues(["a" => "r"],["a"]));
		$clean = $settings->_cleanCookieValues(["a" => "r"],["a"]);
		$this->assertTrue($clean[0] === false);
		$this->assertEquals([false],$settings->_cleanCookieValues(["all_a" => "r"],["all_a"]));
		$this->assertEquals([null],$settings->_cleanCookieValues(["a" => ""],["a"],false));
		$this->assertEquals([null],$settings->_cleanCookieValues(["all_a" => ""],["all_a"],false));

		$this->assertNull($settings->_cleanCookieValues(["a" => "x"],["a"]),"invalid acceptance value");
		$this->assertNull($settings->_cleanCookieValues(["a" => "1"],["a"]),"invalid acceptance value");
		$this->assertNull($settings->_cleanCookieValues(["a" => ""],["a"],true),"value is required");
		$this->assertNull($settings->_cleanCookieValues(["xa" => ""],["a"],true),"key is missing");

		// Category version flag
		// =====================

		$this->assertEquals([1],$settings->_cleanCookieValues(["v" => "1"],["v"]));
		$this->assertEquals([2],$settings->_cleanCookieValues(["v" => "2"],["v"]));
		$this->assertEquals([99],$settings->_cleanCookieValues(["v" => "99"],["v"]));
		$this->assertEquals([9999],$settings->_cleanCookieValues(["v" => "9999"],["v"]));
		$this->assertEquals([null],$settings->_cleanCookieValues(["v" => ""],["v"]));

		$this->assertNull($settings->_cleanCookieValues(["v" => "+1"],["v"]),"invalid version value");
		$this->assertNull($settings->_cleanCookieValues(["v" => "0"],["v"]),"invalid version value");
		$this->assertNull($settings->_cleanCookieValues(["v" => "01"],["v"]),"invalid version value");
		$this->assertNull($settings->_cleanCookieValues(["v" => "xzx"],["v"]),"invalid version value");
		$this->assertNull($settings->_cleanCookieValues(["v" => "1.1"],["v"]),"invalid version format");
		$this->assertNull($settings->_cleanCookieValues(["v" => "99999"],["v"]),"version to high");
		$this->assertNull($settings->_cleanCookieValues(["v" => ""],["v"],true),"required");
	}
}
