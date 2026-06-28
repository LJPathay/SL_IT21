<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleLessonsSeeder extends Seeder
{
    public function run(): void
    {
        $modules = Module::all();
        
        foreach ($modules as $module) {
            $lessonContent = $this->getLessonContent($module->title);
            
            foreach ($lessonContent as $index => $content) {
                Lesson::create([
                    'module_id' => $module->id,
                    'title' => $content['title'],
                    'content' => $content['content'],
                    'order' => $index + 1,
                    'duration_minutes' => 15,
                    'is_published' => true
                ]);
            }
        }
    }

    private function getLessonContent($moduleTitle): array
    {
        $contentMap = [
            'Phishing Email Recognition' => [
                [
                    'title' => 'Introduction to Phishing',
                    'content' => '<h2>Introduction to Phishing</h2>
<p>Phishing is a type of social engineering attack often used to steal user data, including login credentials and credit card numbers. It occurs when an attacker, masquerading as a trusted entity, dupes a victim into opening an email, instant message, or text message.</p>
<h3>Key Learning Points:</h3>
<ul>
<li>Understanding what phishing is and how it works</li>
<li>Common types of phishing attacks</li>
<li>Why phishing is effective</li>
</ul>'
                ],
                [
                    'title' => 'Identifying Phishing Emails',
                    'content' => '<h2>Identifying Phishing Emails</h2>
<p>Learning to identify phishing emails is crucial for protecting yourself and your organization. Here are the most common signs:</p>
<h3>Red Flags to Look For:</h3>
<ul>
<li>Suspicious sender addresses (slight misspellings)</li>
<li>Urgent language demanding immediate action</li>
<li>Generic greetings instead of your name</li>
<li>Unexpected attachments or links</li>
<li>Poor grammar and spelling</li>
</ul>'
                ],
                [
                    'title' => 'Protecting Yourself from Phishing',
                    'content' => '<h2>Protecting Yourself from Phishing</h2>
<p>Now that you can identify phishing attempts, let\'s learn how to protect yourself.</p>
<h3>Best Practices:</h3>
<ul>
<li>Always verify the sender before clicking links</li>
<li>Use multi-factor authentication (MFA)</li>
<li>Keep your software updated</li>
<li>Report suspicious emails to IT security</li>
<li>Use email filtering and security tools</li>
</ul>'
                ]
            ],
            'Password Security Best Practices' => [
                [
                    'title' => 'Understanding Password Vulnerabilities',
                    'content' => '<h2>Understanding Password Vulnerabilities</h2>
<p>Weak passwords are one of the most common security vulnerabilities. Attackers use various methods to crack passwords, including brute force attacks, dictionary attacks, and social engineering.</p>
<h3>Common Password Mistakes:</h3>
<ul>
<li>Using simple words or common phrases</li>
<li>Reusing passwords across multiple accounts</li>
<li>Using personal information in passwords</li>
<li>Not changing default passwords</li>
</ul>'
                ],
                [
                    'title' => 'Creating Strong Passwords',
                    'content' => '<h2>Creating Strong Passwords</h2>
<p>A strong password is your first line of defense against unauthorized access. Here\'s how to create one:</p>
<h3>Password Strength Guidelines:</h3>
<ul>
<li>Use at least 12 characters</li>
<li>Include uppercase and lowercase letters</li>
<li>Add numbers and special characters</li>
<li>Avoid personal information</li>
<li>Use a passphrase instead of a single word</li>
</ul>'
                ],
                [
                    'title' => 'Password Management Tools',
                    'content' => '<h2>Password Management Tools</h2>
<p>Remembering dozens of strong passwords is impossible. Password managers can help.</p>
<h3>Benefits of Password Managers:</h3>
<ul>
<li>Generate and store complex passwords</li>
<li>Auto-fill credentials securely</li>
<li>Alert you to compromised passwords</li>
<li>Enable secure password sharing</li>
<li>Work across all your devices</li>
</ul>'
                ]
            ],
            'Social Engineering Tactics' => [
                [
                    'title' => 'What is Social Engineering?',
                    'content' => '<h2>What is Social Engineering?</h2>
<p>Social engineering is the art of manipulating people to give up confidential information. Unlike technical attacks, social engineering targets the human element of security.</p>
<h3>Common Social Engineering Tactics:</h3>
<ul>
<li>Pretexting (creating a fake scenario)</li>
<li>Baiting (offering something enticing)</li>
<li>Quid pro quo (offering a benefit in exchange)</li>
<li>Tailgating (following someone into secure areas)</li>
</ul>'
                ],
                [
                    'title' => 'Recognizing Social Engineering',
                    'content' => '<h2>Recognizing Social Engineering</h2>
<p>Social engineers are skilled at manipulation. Learn to spot their tactics.</p>
<h3>Warning Signs:</h3>
<ul>
<li>Requests for sensitive information via unusual channels</li>
<li>Pressure to act quickly</li>
<li>Too-good-to-be-true offers</li>
<li>Someone claiming to be authority without verification</li>
<li>Inconsistent stories or details</li>
</ul>'
                ],
                [
                    'title' => 'Defending Against Social Engineering',
                    'content' => '<h2>Defending Against Social Engineering</h2>
<p>Protection against social engineering requires awareness and proper procedures.</p>
<h3>Defense Strategies:</h3>
<ul>
<li>Verify identities through official channels</li>
<li>Follow established procedures for sensitive requests</li>
<li>Trust your instincts - if something feels wrong, question it</li>
<li>Report suspicious attempts immediately</li>
<li>Regular security awareness training</li>
</ul>'
                ]
            ],
            'Malware Analysis Fundamentals' => [
                [
                    'title' => 'Introduction to Malware',
                    'content' => '<h2>Introduction to Malware</h2>
<p>Malware (malicious software) is any program or file that is harmful to a computer user. Understanding malware types is essential for defense.</p>
<h3>Common Malware Types:</h3>
<ul>
<li>Viruses - attach themselves to legitimate programs</li>
<li>Worms - spread across networks automatically</li>
<li>Trojans - disguise themselves as legitimate software</li>
<li>Ransomware - encrypts files for ransom</li>
<li>Spyware - monitors user activity</li>
</ul>'
                ],
                [
                    'title' => 'Malware Infection Vectors',
                    'content' => '<h2>Malware Infection Vectors</h2>
<p>Understanding how malware spreads helps prevent infections.</p>
<h3>Common Infection Methods:</h3>
<ul>
<li>Email attachments and links</li>
<li>Infected websites (drive-by downloads)</li>
<li>Removable media (USB drives)</li>
<li>Software vulnerabilities</li>
<li>Social engineering tactics</li>
</ul>'
                ],
                [
                    'title' => 'Malware Prevention and Removal',
                    'content' => '<h2>Malware Prevention and Removal</h2>
<p>Prevention is better than cure, but knowing how to respond is also important.</p>
<h3>Prevention Measures:</h3>
<ul>
<li>Keep all software updated</li>
<li>Use reputable antivirus/anti-malware</li>
<li>Be cautious with downloads and attachments</li>
<li>Use a firewall</li>
<li>Regular system backups</li>
</ul>'
                ]
            ],
            'Network Security Essentials' => [
                [
                    'title' => 'Network Security Basics',
                    'content' => '<h2>Network Security Basics</h2>
<p>Network security protects your network and data from breaches, intrusions, and other threats.</p>
<h3>Core Network Security Concepts:</h3>
<ul>
<li>Confidentiality - keeping data private</li>
<li>Integrity - ensuring data isn\'t altered</li>
<li>Availability - ensuring systems are accessible</li>
<li>Authentication - verifying user identity</li>
<li>Authorization - controlling access levels</li>
</ul>'
                ],
                [
                    'title' => 'Common Network Threats',
                    'content' => '<h2>Common Network Threats</h2>
<p>Networks face various threats that must be understood and mitigated.</p>
<h3>Network Attack Types:</h3>
<ul>
<li>Man-in-the-Middle (MitM) attacks</li>
<li>Denial of Service (DoS/DDoS)</li>
<li>Packet sniffing</li>
<li>SQL injection</li>
<li>Cross-site scripting (XSS)</li>
</ul>'
                ],
                [
                    'title' => 'Network Security Best Practices',
                    'content' => '<h2>Network Security Best Practices</h2>
<p>Implementing security best practices protects your network infrastructure.</p>
<h3>Essential Practices:</h3>
<ul>
<li>Use strong encryption (WPA3, VPNs)</li>
<li>Implement network segmentation</li>
<li>Regular security audits</li>
<li>Monitor network traffic</li>
<li>Keep firmware and software updated</li>
</ul>'
                ]
            ],
            'Penetration Testing Basics' => [
                [
                    'title' => 'What is Penetration Testing?',
                    'content' => '<h2>What is Penetration Testing?</h2>
<p>Penetration testing (pen testing) simulates cyberattacks to identify vulnerabilities before malicious actors do.</p>
<h3>Types of Pen Testing:</h3>
<ul>
<li>Black box - no prior knowledge</li>
<li>White box - full knowledge of system</li>
<li>Gray box - partial knowledge</li>
<li>Internal vs external testing</li>
</ul>'
                ],
                [
                    'title' => 'Pen Testing Methodology',
                    'content' => '<h2>Pen Testing Methodology</h2>
<p>A structured approach ensures comprehensive testing.</p>
<h3>Testing Phases:</h3>
<ul>
<li>Reconnaissance - gathering information</li>
<li>Scanning - identifying vulnerabilities</li>
<li>Exploitation - attempting to breach</li>
<li>Post-exploitation - maintaining access</li>
<li>Reporting - documenting findings</li>
</ul>'
                ],
                [
                    'title' => 'Common Pen Testing Tools',
                    'content' => '<h2>Common Pen Testing Tools</h2>
<p>Various tools help security professionals identify vulnerabilities.</p>
<h3>Popular Tools:</h3>
<ul>
<li>Nmap - network scanning</li>
<li>Burp Suite - web application testing</li<li>Metasploit - exploitation framework</li>
<li>Wireshark - packet analysis</li>
<li>John the Ripper - password cracking</li>
</ul>'
                ]
            ],
            'GDPR Compliance Overview' => [
                [
                    'title' => 'Introduction to GDPR',
                    'content' => '<h2>Introduction to GDPR</h2>
<p>The General Data Protection Regulation (GDPR) is a regulation in EU law on data protection and privacy.</p>
<h3>Key GDPR Principles:</h3>
<ul>
<li>Lawfulness, fairness, and transparency</li>
<li>Purpose limitation</li>
<li>Data minimization</li>
<li>Accuracy</li>
<li>Storage limitation</li>
</ul>'
                ],
                [
                    'title' => 'GDPR Rights for Individuals',
                    'content' => '<h2>GDPR Rights for Individuals</h2>
<p>GDPR grants specific rights to individuals regarding their personal data.</p>
<h3>Data Subject Rights:</h3>
<ul>
<li>Right to be informed</li>
<li>Right of access</li>
<li>Right to rectification</li>
<li>Right to erasure (right to be forgotten)</li>
<li>Right to data portability</li>
</ul>'
                ],
                [
                    'title' => 'GDPR Compliance Requirements',
                    'content' => '<h2>GDPR Compliance Requirements</h2>
<p>Organizations must implement specific measures to comply with GDPR.</p>
<h3>Compliance Steps:</h3>
<ul>
<li>Conduct data audits</li>
<li>Appoint a Data Protection Officer</li>
<li>Implement privacy by design</li>
<li>Ensure lawful basis for processing</li>
<li>Maintain records of processing activities</li>
</ul>'
                ]
            ],
            'Data Classification and Handling' => [
                [
                    'title' => 'Understanding Data Classification',
                    'content' => '<h2>Understanding Data Classification</h2>
<p>Data classification organizes data based on sensitivity and regulatory requirements.</p>
<h3>Common Classification Levels:</h3>
<ul>
<li>Public - can be freely shared</li>
<li>Internal - organization use only</li>
<li>Confidential - restricted access</li>
<li>Restricted - highest sensitivity</li>
</ul>'
                ],
                [
                    'title' => 'Data Handling Procedures',
                    'content' => '<h2>Data Handling Procedures</h2>
<p>Each classification level requires specific handling procedures.</p>
<h3>Handling Guidelines:</h3>
<ul>
<li>Follow classification labels strictly</li>
<li>Use appropriate storage and transmission methods</li>
<li>Implement access controls</li>
<li>Document data access and transfers</li>
<li>Regular security reviews</li>
</ul>'
                ],
                [
                    'title' => 'Data Lifecycle Management',
                    'content' => '<h2>Data Lifecycle Management</h2>
<p>Data should be managed throughout its entire lifecycle.</p>
<h3>Lifecycle Stages:</h3>
<ul>
<li>Creation - classify at point of creation</li>
<li>Storage - secure based on classification</li>
<li>Usage - control access and monitor</li>
<li>Archival - move to long-term storage</li>
<li>Destruction - secure disposal when no longer needed</li>
</ul>'
                ]
            ],
            'System Hardening Guide' => [
                [
                    'title' => 'Introduction to System Hardening',
                    'content' => '<h2>Introduction to System Hardening</h2>
<p>System hardening reduces vulnerabilities and strengthens security defenses.</p>
<h3>Hardening Goals:</h3>
<ul>
<li>Reduce attack surface</li>
<li>Eliminate unnecessary services</li>
<li>Apply security configurations</li>
<li>Implement monitoring and logging</li>
</ul>'
                ],
                [
                    'title' => 'Operating System Hardening',
                    'content' => '<h2>Operating System Hardening</h2>
<p>OS hardening focuses on securing the base system.</p>
<h3>Key Hardening Steps:</h3>
<ul>
<li>Remove unnecessary software and services</li>
<li>Apply all security patches</li>
<li>Configure strong authentication</li>
<li>Disable unused ports and protocols</li>
<li>Enable firewall and intrusion detection</li>
</ul>'
                ],
                [
                    'title' => 'Application Hardening',
                    'content' => '<h2>Application Hardening</h2>
<p>Applications must be secured against common vulnerabilities.</p>
<h3>Application Security Measures:</h3>
<ul>
<li>Input validation and sanitization</li>
<li>Output encoding</li>
<li>Secure authentication and authorization</li>
<li>Error handling and logging</li>
<li>Regular security testing</li>
</ul>'
                ]
            ],
            'Incident Response Planning' => [
                [
                    'title' => 'Introduction to Incident Response',
                    'content' => '<h2>Introduction to Incident Response</h2>
<p>Incident response is the organized approach to addressing and managing security incidents.</p>
<h3>Incident Response Goals:</h3>
<ul>
<li>Minimize impact and damage</li>
<li>Restore normal operations quickly</li>
<li>Identify root causes</li>
<li>Prevent future incidents</li>
</ul>'
                ],
                [
                    'title' => 'Incident Response Phases',
                    'content' => '<h2>Incident Response Phases</h2>
<p>A structured incident response process follows defined phases.</p>
<h3>NIST Incident Response Lifecycle:</h3>
<ul>
<li>Preparation - establish procedures and tools</li>
<li>Detection and Analysis - identify incidents</li>
<li>Containment, Eradication, Recovery - respond to incidents</li>
<li>Post-Incident Activity - learn and improve</li>
</ul>'
                ],
                [
                    'title' => 'Building an Incident Response Plan',
                    'content' => '<h2>Building an Incident Response Plan</h2>
<p>A well-documented plan ensures effective response to security incidents.</p>
<h3>Plan Components:</h3>
<ul>
<li>Incident classification and severity levels</li>
<li>Roles and responsibilities</li>
<li>Communication procedures</li>
<li>Technical response procedures</li>
<li>Recovery and restoration steps</li>
</ul>'
                ]
            ]
        ];

        return $contentMap[$moduleTitle] ?? [
            [
                'title' => 'Lesson 1: Introduction',
                'content' => '<h2>Introduction</h2><p>Welcome to this module on ' . $moduleTitle . '.</p>'
            ],
            [
                'title' => 'Lesson 2: Core Concepts',
                'content' => '<h2>Core Concepts</h2><p>Learn the fundamental concepts of ' . $moduleTitle . '.</p>'
            ],
            [
                'title' => 'Lesson 3: Best Practices',
                'content' => '<h2>Best Practices</h2><p>Implement best practices for ' . $moduleTitle . '.</p>'
            ]
        ];
    }
}
