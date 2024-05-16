@extends('front.layout')

@section('body')
    <main class="page-body d-flex flex-column container-xl">
        <aside class="page-title container-xl">
            <h1 class='title'>Welcome to Clovercraft!</h1>
            <h2 class='subtitle'>An open and supportive gaming community</h2>
        </aside>
        <x-cms-banner />
        <section class="page-content container-xl">
            <h3>Who We Are</h3>
            <p>Our mission is to foster a high quality and supporting minecraft community, where adults from all walks of
                life can connect, collaborate, and build amazing adventures together. Our members come from all over the
                world, and each bring unique new energy to our little community.</p>
            <h3>Get Involved</h3>
            <p>Joining the Clovercraft community is as easy as heading over to our <a href="https://discord.gg/clovercraft"
                    target="_blank">Discord server</a>. Once you're there, hop into the introductions channel and say hello!
                We recommend all new members check the <strong>Channels & Roles</strong> section of the Discord to make sure
                you follow all the channels you're interested in, and pick up some notification roles.
            </p>
            <p>Once you're all set up on Discord, you can log in to the <a href="{{ route('platform.login') }}">HUB</a> and
                add your Minecraft account to our whitelist. Here's a few other things our members get access to:</p>
            <ul>
                <li>The Clovercraft SMP (<a href="https://wiki.clovercraft.gg/en/player-guide/server-customizations"
                        target="_blank">read more</a>)</li>
                <li>Regularly scheduled community game nights</li>
                <li>Discord movie nights</li>
                <li>SMP Quests and storylines made by staff and players</li>
                <li>Limited time alternative SMPs</li>
                <li>Clovercraft guilds in other games!</li>
            </ul>
            <h3>Contact Our Team</h3>
            <p>The quickest way to get ahold of our staff team is through our <a href="https://discord.gg/clovercraft"
                    target="_blank">Discord</a>. If you do not have a Discord account, or need to reach us but cannot join
                the server, you can <a href="mailto:clovercraftmf@gmail.com" target="_blank">email our team</a> at any time
                and we will do our best to respond in a timely manner.</p>
            <h3>Non-Affiliation Disclaimer</h3>
            <p>Clovercraft and our volunteer staff team are in no way affiliated with Mojang AB or Microsoft. Clovercraft
                members may publish their own content from the Clovercraft SMP, given that they comply with the <a
                    href="https://aka.ms/MCUsageGuidelines" target="_blank">Minecraft Usage Guidelines</a> and <a
                    href="http://aka.ms/MinecraftEULA" target="_blank">EULA</a>.</p>
        </section>
    </main>
@endsection
