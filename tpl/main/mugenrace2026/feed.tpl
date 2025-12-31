<?xml version="1.0" encoding="utf8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title><![CDATA[{$title}]]></title>
		<link>{$link}</link>
		<description><![CDATA[{$description}]]></description>
		<atom:link href="{$link}" rel="self" type="application/rss+xml" />
		<pubDate>{$pubdate} +0200</pubDate>
		<lastBuildDate>{$lastbuilddate} +0200</lastBuildDate>
		<generator>SIIKerES</generator>
		<language>hu</language>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		{foreach $entries as $entry}
		<item>
			<title><![CDATA[{$entry.title}]]></title>
			<link>{$entry.link}</link>
			<guid>{$entry.link}</guid>
			<description><![CDATA[{$entry.description}]]></description>
			<pubDate>{$entry.pubdate} +0200</pubDate>
		</item>
		{/foreach}
	</channel>
</rss>