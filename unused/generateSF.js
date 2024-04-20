const https = require('https');
const fs = require('fs');
function downloadFile(url, destination) {
    return new Promise((resolve, reject) => {
        const file = fs.createWriteStream(destination);
        https.get(url, response => {
            response.pipe(file);
            file.on('finish', () => {
                file.close(resolve(destination));
            });
        }).on('error', err => {
            fs.unlink(destination, () => reject(err));
        });
    });
}
function readFileContent(filePath) {
    return fs.readFileSync(filePath, 'utf-8');
}
function createPHPArray(words) {
    let phpArray = "$swears = array(\n";
    words.forEach(word => {
        phpArray += `    "${word}" => "${'_'.repeat(word.length)}",\n`;
    });
    phpArray += ");";
    return phpArray;
}
async function main() {
    const url = 'https://cdn.jsdelivr.net/gh/snguyenthanh/better-profanity@0.7.0/better_profanity/profanity_wordlist.txt';
    const destination = 'profanity_wordlist.txt';

    try {
        // Download the file
        await downloadFile(url, destination);
        console.log('downloaded swear list successfully!');

        await downloadFile("https://c.dumo.se/sfsrc.txt", 'template.txt');
        console.log('downloaded swearfilter template successfully!');

        // Read file content
        const content = readFileContent(destination);

        //read other file content
        const otherContent = readFileContent("template.txt");

        // Split content by lines
        const wordsArray = content.split('\n').map(word => word.trim());

        // Create PHP array
        const phpArray = createPHPArray(wordsArray);

        // Write PHP array to a file
        fs.writeFileSync('swearfilter.php', otherContent.replace("{{ swears }}", phpArray));
        console.log('generated successfully!');

        console.log('deleting swearlist and template..');
        fs.unlink("profanity_wordlist.txt", () => console.log("deleted swearlist"));
        fs.unlink("template.txt", () => console.log("deleted template"));

        console.log("success!")
    } catch (error) {
        console.error('Error:', error);
    }
}

// Run the main function
main();
