INFORMATION:
When we mean PLEDGE its for donors who want to pledge to give items. When we mean
DONATE we mean donors who want to give cash.
Questions:
● Will the website work when i open this website on my phone? Tablet?
● What do we mean exactly when we talk conversion, when someone gives money na no?
● I want to track tani the money that is being sent if ever donors submit to NGOs via qrph
or websites but im not sure how does that work out technically
GENERAL CHANGES:

NGO SHOULD NOT Make donation drives (like the admin/dswd). NGO should only have
the option to put their WEBSITE and/or QR Codes for Money Transfer.
NGO has same roles as USER BUT only NGO has the power to put up their donation
link/ QR(gcash maya bank qrs qrph) to receive payment. AND NGO can see exact data
of how many items exactly needed
○ Therefore the NGO should be able to submit transparency reports to the website
so that donors can see where their money has gone to
ADMIN/DSWD SHOULD NOT have the option to receive money
NGO function: they can click a button on an active donation drive named “SUPPORT”,
its them confirming to donors (when the donor decides they cant pledge so they want to
donate towards a certain cause) that they are willing to receive money for this cause.
○ Basically in the UI that we updated, donor sees donate and pledge, NGO sees
SUPPORT and pledge, and ngo can see exact items needed
The progress bar needs to be colored 3 ways: 1. Needed quantity (empty), 2. Pledged
quantity (total amount of pledged items from all donors compiled to show to all), 3.
Distributed quantity.
DONOR cannot see exactly how many items, just a progress bar
DONOR can see when they click DONATE the different orgs that wish to help towards a
specific
ADMIN TWEAKS:
When admin wants to create a drive, they can decide whether they want to: a) input only
the families affected (this will then use the mother formulas based on what kind of
PACKS they need) OR b.) input manually what kind of items they need and their
quantity.
2.. When admin wants to list down items needed, it should be like google forms where in they
can pick a common donation good and on its right pick the quantity (for example RICE - 100KG.
WATER- 100L. CANNED GOODS - 100pcs.)
To clarify: when admin wants to create a drive, they input the number of families affected,
then it will automatically list down the quantity based on the mother formula. The admin also has
the option to manually add an item, and its quantity. They can also indicate if its a type of item
that isnt in the database, under “others”
MOTHER FORMULA:
Food packs
6KG Rice
5 Sachets coffee
5 sachets powdered cereal drink
4 Tins Corned Beef
4 Tins Tuna
2 Tins Sardines
Kitchen kit
● 5 pieces spoon;
● 5 pieces fork;
● 5 pieces drinking glass;
● 5 pieces plate;
● 1 piece frying pan;
● 1 piece cooking pan;
● 1 piece ladle; and
● 1 piece packaging material.
HYGIENE KIT
● 5 pieces toothbrush;
● 2 pieces Toothpaste;
● 1 bottle shampoo;
● 4 pieces bath bar soap;
● 2,000 grams laundry bar soap;
● 4 packs sanitary napkin;
● 1 piece comb;
● 1 piece disposable shaving razor;
● 1 piece nail cutter;
● 1 piece bathroom dipper; and
● 1 piece 20L square plastic bucket, with deep cover and plastic handle.
SLEEPING KIT
● 1 piece blanket;
● 1 piece plastic mat;
● 1 piece mosquito net;
● 1 piece malong (wrap cloth); and
● 1 piece packaging material.
●
Family Clothing Kit
● 5 pieces bath towel;
● 2 pieces ladies’ panty;
● 3 pieces girls’ panty;
● 2 pieces men’s brief;
● 3 pieces boys’ brief;
● 2 pieces sando bra, adult;
● 3 pieces sando bra, girls;
● 4 pieces adults’ t-shirt;
● 6 pieces children’s T-shirt;
● 4 pieces adults’ short pants;
● 6 pieces children’s short;
● 2 pairs adults’ slippers;
● 3 pairs children’s slipper; and
● 1 piece packaging material.
if any of these requirements are met, consider it 1 family helped.
special cases: if donor A can only donate tuna, for example they give 40 cans, then consider it
as they helped 10 families, then donor gets notified when donation is delivered
special cases 2: if the donor B has donated 40 cans of tuna AND 10 tins of sardines, let the
donor be notified 2 times (when admin confirms it is distributed), 1 notification regarding how

many families the cans of tuna helped (in this case 10) and another notification regarding the
sardines (in this case, 5 families have been helped)
Example of notif block: Your donation of 40 Tins of Tuna has helped 10 Families
IF donor gives amnt of items that doesnt cleanly divide with the quantity in the mother formula,
round it up

Under ADMIN MANAGE DONATION DRIVES: add start date
UPDATED SECTION 8
8. Uncertain Areas & Open Questions
The following areas require specific logic definitions before implementation can proceed:
Impact Calculation Logic :
Question : How exactly do we convert "Items Distributed" into "Families Helped" or "Relief
Packages"?
Need : A conversion table (e.g., 5kg Rice + 3 Canned Goods = 1 Family Pack) or a manual
override field for Admins.
answ: we can use the markers i sent in the PDF nalang since these are official packs stat
if any of these requirements are met, consider it 1 family helped.
special cases: if donor A can only donate tuna, for example they give 40 cans, then consider it
as they helped 10 families, then donor gets notified when donation is delivered
special cases 2: if the donor B has donated 40 cans of tuna AND 10 tins of sardines, let the
donor be notified 2 times (when admin confirms it is distributed), 1 notification regarding how
many families the cans of tuna helped (in this case 10) and another notification regarding the
sardines (in this case, 5 families have been helped)

Conversion Rate Tracking :
Question : How do we confirm a "Conversion" on an external NGO website?
Constraint : If the link leads off-platform, we can track "Clicks" easily. However, "Conversion"
(actual donation on their site) requires an API Callback or a "Thank You" page pixel from their
site to ours.
Current Assumption : We will track "Link Clicks" only, unless an API integration is approved.
answ: we want to discuss with you this tani since we wish man to track the money to show on
the page how much has been raised
Share Campaign Mechanics :
Question : When an NGO shares a campaign, where does the user land?
Issue : If the user clicks a shared link but isn't logged in, they cannot see the internal "Pledge"
page.
Suggestion : Shared links should lead to a public-facing "Drive Preview" page that prompts
Login/Signup before pledging.

gets gets, i feel like this is overlooked sa amon end, please add this
Financial Transparency Reports :
Question : Since the platform primarily tracks goods (pledges of items), where does the financial
data come from?
Need : Does the "Target Amount" in Create Drive refer to the value of goods, or actual cash? If
cash is involved, we need a Payment Gateway. If it's just value estimation, we need a "Price per
Item" master list to calculate reports.
answ: actual cash, im trying to discuss with the team how exactly can we track money